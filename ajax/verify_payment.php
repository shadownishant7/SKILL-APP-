<?php
require_once '../common/config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$razorpay_order_id = $input['razorpay_order_id'] ?? '';
$razorpay_payment_id = $input['razorpay_payment_id'] ?? '';
$razorpay_signature = $input['razorpay_signature'] ?? '';
$course_id = $input['course_id'] ?? 0;

if (!$razorpay_order_id || !$razorpay_payment_id || !$razorpay_signature || !$course_id) {
    echo json_encode(['success' => false, 'message' => 'Missing payment details']);
    exit;
}

// Get Razorpay keys from settings
$settings = getSettings();
$razorpay_key_id = $settings['razorpay_key'] ?? 'rzp_test_YOUR_KEY_ID';
$razorpay_key_secret = $settings['razorpay_secret'] ?? 'YOUR_SECRET_KEY';

// Verify signature
$expected_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $razorpay_key_secret);

if ($expected_signature !== $razorpay_signature) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment signature']);
    exit;
}

// Verify payment with Razorpay
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/payments/' . $razorpay_payment_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode($razorpay_key_id . ':' . $razorpay_key_secret)
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    echo json_encode(['success' => false, 'message' => 'Failed to verify payment with Razorpay']);
    exit;
}

$payment_data = json_decode($response, true);

// Check if payment is successful
if ($payment_data['status'] !== 'captured') {
    echo json_encode(['success' => false, 'message' => 'Payment not completed']);
    exit;
}

// Update order status
$update_query = "UPDATE orders SET status = 'completed' WHERE user_id = ? AND course_id = ? AND razorpay_order_id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("iis", $_SESSION['user_id'], $course_id, $razorpay_order_id);

if ($update_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Payment verified successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
}
?>