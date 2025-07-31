<?php
require_once '../common/config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$course_id = $input['course_id'] ?? 0;
$amount = $input['amount'] ?? 0;
$customer_name = $input['customer_name'] ?? '';
$customer_email = $input['customer_email'] ?? '';
$customer_phone = $input['customer_phone'] ?? '';

if (!$course_id || !$amount || !$customer_name || !$customer_email || !$customer_phone) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Verify course exists
$course_query = "SELECT * FROM courses WHERE id = ?";
$course_stmt = $conn->prepare($course_query);
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course_result = $course_stmt->get_result();

if ($course_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Course not found']);
    exit;
}

$course = $course_result->fetch_assoc();

// Check if already purchased
$purchase_query = "SELECT * FROM orders WHERE user_id = ? AND course_id = ? AND status = 'completed'";
$purchase_stmt = $conn->prepare($purchase_query);
$purchase_stmt->bind_param("ii", $_SESSION['user_id'], $course_id);
$purchase_stmt->execute();
$purchase_result = $purchase_stmt->get_result();

if ($purchase_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Course already purchased']);
    exit;
}

// Create Razorpay order
$razorpay_key_id = 'rzp_test_YOUR_KEY_ID'; // Replace with your test key
$razorpay_key_secret = 'YOUR_SECRET_KEY'; // Replace with your test secret

$order_data = [
    'receipt' => 'order_' . time(),
    'amount' => $amount * 100, // Convert to paise
    'currency' => 'INR',
    'notes' => [
        'course_id' => $course_id,
        'user_id' => $_SESSION['user_id']
    ]
];

// Create order using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($razorpay_key_id . ':' . $razorpay_key_secret)
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200) {
    $razorpay_order = json_decode($response, true);
    
    // Store order in database
    $order_query = "INSERT INTO orders (user_id, course_id, amount, razorpay_order_id, status) VALUES (?, ?, ?, ?, 'pending')";
    $order_stmt = $conn->prepare($order_query);
    $order_stmt->bind_param("iids", $_SESSION['user_id'], $course_id, $amount, $razorpay_order['id']);
    
    if ($order_stmt->execute()) {
        echo json_encode([
            'success' => true,
            'order_id' => $razorpay_order['id'],
            'amount' => $razorpay_order['amount'],
            'currency' => $razorpay_order['currency'],
            'key_id' => $razorpay_key_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to store order']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create Razorpay order']);
}
?>