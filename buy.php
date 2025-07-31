<?php
require_once 'common/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$course_id = $_GET['id'] ?? 0;

if (!$course_id) {
    redirect('course.php');
}

// Get course details
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('course.php');
}

$course = $result->fetch_assoc();

// Check if already purchased
$purchase_query = "SELECT * FROM orders WHERE user_id = ? AND course_id = ? AND status = 'completed'";
$purchase_stmt = $conn->prepare($purchase_query);
$purchase_stmt->bind_param("ii", $_SESSION['user_id'], $course_id);
$purchase_stmt->execute();
$purchase_result = $purchase_stmt->get_result();

if ($purchase_result->num_rows > 0) {
    redirect('watch.php?id=' . $course_id);
}

// Get user details
$user = getCurrentUser();
?>

<?php include 'common/header.php'; ?>

<div class="max-w-md mx-auto">
    <!-- Course Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center mb-4">
            <img src="<?php echo $course['thumbnail'] ?: 'https://via.placeholder.com/100x100/4F46E5/FFFFFF?text=Course'; ?>" 
                 alt="<?php echo htmlspecialchars($course['title']); ?>"
                 class="w-16 h-16 object-cover rounded-lg mr-4">
            <div>
                <h2 class="font-semibold text-gray-800"><?php echo htmlspecialchars($course['title']); ?></h2>
                <p class="text-sm text-gray-600">Course Purchase</p>
            </div>
        </div>
        
        <div class="border-t border-gray-200 pt-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Course Price:</span>
                <span class="text-lg font-bold text-blue-600">₹<?php echo $course['price']; ?></span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">MRP:</span>
                <span class="text-gray-500 line-through">₹<?php echo $course['mrp']; ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Discount:</span>
                <span class="text-green-600 font-medium">
                    <?php 
                    $discount = round((($course['mrp'] - $course['price']) / $course['mrp']) * 100);
                    echo $discount . '% OFF';
                    ?>
                </span>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">Billing Information</h3>
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" id="customerName" value="<?php echo htmlspecialchars($user['name']); ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="customerEmail" value="<?php echo htmlspecialchars($user['email']); ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="tel" id="customerPhone" value="<?php echo htmlspecialchars($user['phone']); ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Payment Button -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-4">
            <div class="text-2xl font-bold text-gray-800 mb-1">Total Amount</div>
            <div class="text-3xl font-bold text-blue-600">₹<?php echo $course['price']; ?></div>
        </div>
        
        <button id="payButton" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 flex items-center justify-center">
            <i class="fas fa-credit-card mr-2"></i>
            Pay Now
        </button>
        
        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">Secure payment powered by Razorpay</p>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 text-center">
        <i class="fas fa-spinner fa-spin text-blue-600 text-2xl mb-2"></i>
        <p class="text-gray-700">Processing payment...</p>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
const payButton = document.getElementById('payButton');
const loadingOverlay = document.getElementById('loadingOverlay');

payButton.addEventListener('click', function() {
    // Validate form
    const customerName = document.getElementById('customerName').value.trim();
    const customerEmail = document.getElementById('customerEmail').value.trim();
    const customerPhone = document.getElementById('customerPhone').value.trim();
    
    if (!customerName || !customerEmail || !customerPhone) {
        alert('Please fill in all billing information');
        return;
    }
    
    // Show loading
    loadingOverlay.classList.remove('hidden');
    
    // Create order
    fetch('ajax/create_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            course_id: <?php echo $course_id; ?>,
            amount: <?php echo $course['price']; ?>,
            customer_name: customerName,
            customer_email: customerEmail,
            customer_phone: customerPhone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Initialize Razorpay
            const options = {
                key: data.key_id, // Replace with your Razorpay key
                amount: data.amount,
                currency: data.currency,
                name: 'Skills With Nishant',
                description: '<?php echo htmlspecialchars($course['title']); ?>',
                order_id: data.order_id,
                handler: function(response) {
                    // Payment successful
                    verifyPayment(response);
                },
                prefill: {
                    name: customerName,
                    email: customerEmail,
                    contact: customerPhone
                },
                theme: {
                    color: '#3B82F6'
                }
            };
            
            const rzp = new Razorpay(options);
            rzp.open();
        } else {
            alert(data.message || 'Failed to create order');
        }
        loadingOverlay.classList.add('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        loadingOverlay.classList.add('hidden');
    });
});

function verifyPayment(response) {
    loadingOverlay.classList.remove('hidden');
    
    fetch('ajax/verify_payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            razorpay_order_id: response.razorpay_order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_signature: response.razorpay_signature,
            course_id: <?php echo $course_id; ?>
        })
    })
    .then(response => response.json())
    .then(data => {
        loadingOverlay.classList.add('hidden');
        if (data.success) {
            alert('Payment successful! You can now access your course.');
            window.location.href = 'mycourses.php';
        } else {
            alert(data.message || 'Payment verification failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during payment verification.');
        loadingOverlay.classList.add('hidden');
    });
}
</script>

<?php include 'common/bottom.php'; ?>