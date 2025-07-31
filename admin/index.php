<?php
require_once '../common/config.php';

// Check if admin is logged in
if (!isAdmin()) {
    redirect('login.php');
}

// Get statistics
$stats = [];

// Total users
$users_query = "SELECT COUNT(*) as count FROM users";
$users_result = $conn->query($users_query);
$stats['users'] = $users_result->fetch_assoc()['count'];

// Total revenue
$revenue_query = "SELECT SUM(amount) as total FROM orders WHERE status = 'completed'";
$revenue_result = $conn->query($revenue_query);
$stats['revenue'] = $revenue_result->fetch_assoc()['total'] ?? 0;

// Active courses
$courses_query = "SELECT COUNT(*) as count FROM courses";
$courses_result = $conn->query($courses_query);
$stats['courses'] = $courses_result->fetch_assoc()['count'];

// Total purchases
$purchases_query = "SELECT COUNT(*) as count FROM orders WHERE status = 'completed'";
$purchases_result = $conn->query($purchases_query);
$stats['purchases'] = $purchases_result->fetch_assoc()['count'];

// Recent orders
$recent_orders_query = "SELECT o.*, u.name as user_name, c.title as course_title 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        JOIN courses c ON o.course_id = c.id 
                        ORDER BY o.created_at DESC 
                        LIMIT 5";
$recent_orders_result = $conn->query($recent_orders_query);

// Recent users
$recent_users_query = "SELECT * FROM users ORDER BY created_at DESC LIMIT 5";
$recent_users_result = $conn->query($recent_users_query);
?>

<?php include 'common/header.php'; ?>

<!-- Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Dashboard</h1>
    <p class="text-gray-600">Welcome back, Admin!</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo $stats['users']; ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-rupee-sign text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-800">₹<?php echo number_format($stats['revenue']); ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Courses</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo $stats['courses']; ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Purchases</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo $stats['purchases']; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <a href="course.php" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
        <i class="fas fa-plus text-3xl text-blue-600 mb-3"></i>
        <h3 class="font-semibold text-gray-800 mb-1">Add Course</h3>
        <p class="text-sm text-gray-600">Create a new course</p>
    </a>
    
    <a href="banner.php" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
        <i class="fas fa-images text-3xl text-green-600 mb-3"></i>
        <h3 class="font-semibold text-gray-800 mb-1">Add Banner</h3>
        <p class="text-sm text-gray-600">Upload banner images</p>
    </a>
    
    <a href="users.php" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
        <i class="fas fa-users text-3xl text-purple-600 mb-3"></i>
        <h3 class="font-semibold text-gray-800 mb-1">Manage Users</h3>
        <p class="text-sm text-gray-600">View user details</p>
    </a>
    
    <a href="orders.php" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow">
        <i class="fas fa-shopping-cart text-3xl text-orange-600 mb-3"></i>
        <h3 class="font-semibold text-gray-800 mb-1">View Orders</h3>
        <p class="text-sm text-gray-600">Check recent orders</p>
    </a>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Orders</h3>
        <?php if ($recent_orders_result->num_rows > 0): ?>
            <div class="space-y-3">
                <?php while ($order = $recent_orders_result->fetch_assoc()): ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($order['user_name']); ?></p>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($order['course_title']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-green-600">₹<?php echo $order['amount']; ?></p>
                            <p class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-500 text-center py-4">No recent orders</p>
        <?php endif; ?>
    </div>
    
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Users</h3>
        <?php if ($recent_users_result->num_rows > 0): ?>
            <div class="space-y-3">
                <?php while ($user = $recent_users_result->fetch_assoc()): ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($user['name']); ?></p>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-500 text-center py-4">No recent users</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'common/bottom.php'; ?>