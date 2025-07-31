<?php require_once '../common/config.php'; 
$settings = getSettings();
$app_name = $settings['app_name'] ?? 'Skills With Nishant';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo htmlspecialchars($app_name); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Theme color: #0284C7 */
        .theme-bg { background-color: #0284C7; }
        .theme-text { color: #0284C7; }
        .theme-border { border-color: #0284C7; }
        
        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="theme-bg text-white shadow-sm fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center justify-between px-4 py-3">
            <!-- Menu Icon -->
            <button id="menuBtn" class="text-white hover:text-gray-200">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <!-- App Name -->
            <h1 class="text-lg font-bold text-white">Admin Panel</h1>
            
            <!-- Admin Info -->
            <div class="flex items-center space-x-3">
                <span class="text-sm text-white">Admin</span>
                <a href="logout.php" class="text-red-300 hover:text-red-200">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-40">
        <div class="p-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-800">Admin Menu</h2>
                <button id="closeSidebar" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="space-y-2">
                <a href="index.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="banner.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-images mr-3"></i>
                    <span>Banners</span>
                </a>
                <a href="course.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-book mr-3"></i>
                    <span>Courses</span>
                </a>
                <a href="chapter.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-list mr-3"></i>
                    <span>Chapters</span>
                </a>
                <a href="video.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-video mr-3"></i>
                    <span>Videos</span>
                </a>
                <a href="users.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-users mr-3"></i>
                    <span>Users</span>
                </a>
                <a href="orders.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    <span>Orders</span>
                </a>
                <a href="payments.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-credit-card mr-3"></i>
                    <span>Payments</span>
                </a>
                <a href="settings.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-30 hidden"></div>

    <!-- Main Content -->
    <main class="pt-16 pb-6">
        <div class="container mx-auto px-4">