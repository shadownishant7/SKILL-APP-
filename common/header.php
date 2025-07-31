<?php require_once 'config.php'; 
$settings = getSettings();
$app_name = $settings['app_name'] ?? 'Skills With Nishant';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($app_name); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Theme color: #0284C7 */
        .theme-bg { background-color: #0284C7; }
        .theme-text { color: #0284C7; }
        .theme-border { border-color: #0284C7; }
        
        /* Disable right-click and text selection */
        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Disable pinch-to-zoom */
        html {
            touch-action: manipulation;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
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
            <h1 class="text-lg font-bold text-white"><?php echo htmlspecialchars($app_name); ?></h1>
            
            <!-- Profile Icon -->
            <a href="profile.php" class="text-white hover:text-gray-200">
                <i class="fas fa-user-circle text-xl"></i>
            </a>
        </div>
    </header>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-40">
        <div class="p-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-800">Menu</h2>
                <button id="closeSidebar" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <?php if (isLoggedIn()): ?>
                <div class="mb-4 p-3 bg-gray-100 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-2xl text-gray-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800"><?php echo getCurrentUser()['name']; ?></p>
                            <p class="text-sm text-gray-600"><?php echo getCurrentUser()['email']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <nav class="space-y-2">
                <a href="index.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-home mr-3"></i>
                    <span>Home</span>
                </a>
                <a href="course.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-book mr-3"></i>
                    <span>All Courses</span>
                </a>
                <?php if (isLoggedIn()): ?>
                    <a href="mycourses.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-graduation-cap mr-3"></i>
                        <span>My Courses</span>
                    </a>
                    <a href="profile.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-user mr-3"></i>
                        <span>Profile</span>
                    </a>
                    <a href="help.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-question-circle mr-3"></i>
                        <span>Help</span>
                    </a>
                    <a href="logout.php" class="flex items-center p-3 text-red-600 hover:bg-red-50 rounded-lg">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-sign-in-alt mr-3"></i>
                        <span>Login</span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-30 hidden"></div>

    <!-- Main Content -->
    <main class="pt-16 pb-20">
        <div class="container mx-auto px-4">