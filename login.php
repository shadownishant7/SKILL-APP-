<?php
require_once 'common/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Skills With Nishant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Skills With Nishant</h1>
                <p class="text-gray-600">Learn, Grow, Succeed</p>
            </div>

            <!-- Login/Signup Tabs -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex mb-6">
                    <button id="loginTab" class="flex-1 py-2 px-4 text-center font-medium text-blue-600 border-b-2 border-blue-600">
                        Login
                    </button>
                    <button id="signupTab" class="flex-1 py-2 px-4 text-center font-medium text-gray-500 border-b-2 border-transparent">
                        Sign Up
                    </button>
                </div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                        Login
                    </button>
                </form>

                <!-- Signup Form -->
                <form id="signupForm" class="space-y-4 hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200">
                        Sign Up
                    </button>
                </form>

                <!-- Loading and Messages -->
                <div id="loading" class="hidden text-center py-4">
                    <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
                    <p class="text-gray-600 mt-2">Processing...</p>
                </div>

                <div id="message" class="hidden mt-4 p-3 rounded-lg"></div>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        const loginTab = document.getElementById('loginTab');
        const signupTab = document.getElementById('signupTab');
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');

        function showLogin() {
            loginTab.classList.add('text-blue-600', 'border-blue-600');
            loginTab.classList.remove('text-gray-500', 'border-transparent');
            signupTab.classList.add('text-gray-500', 'border-transparent');
            signupTab.classList.remove('text-blue-600', 'border-blue-600');
            loginForm.classList.remove('hidden');
            signupForm.classList.add('hidden');
        }

        function showSignup() {
            signupTab.classList.add('text-blue-600', 'border-blue-600');
            signupTab.classList.remove('text-gray-500', 'border-transparent');
            loginTab.classList.add('text-gray-500', 'border-transparent');
            loginTab.classList.remove('text-blue-600', 'border-blue-600');
            signupForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
        }

        loginTab.addEventListener('click', showLogin);
        signupTab.addEventListener('click', showSignup);

        // Form submission
        const loading = document.getElementById('loading');
        const message = document.getElementById('message');

        function showMessage(text, type) {
            message.textContent = text;
            message.className = `mt-4 p-3 rounded-lg ${type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
            message.classList.remove('hidden');
        }

        function showLoading() {
            loading.classList.remove('hidden');
            message.classList.add('hidden');
        }

        function hideLoading() {
            loading.classList.add('hidden');
        }

        // Login form submission
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showLoading();

            const formData = new FormData(loginForm);
            
            fetch('ajax/login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showMessage('Login successful! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 1000);
                } else {
                    showMessage(data.message || 'Login failed', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showMessage('An error occurred. Please try again.', 'error');
            });
        });

        // Signup form submission
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showLoading();

            const formData = new FormData(signupForm);
            
            fetch('ajax/signup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showMessage('Registration successful! Please login.', 'success');
                    showLogin();
                    signupForm.reset();
                } else {
                    showMessage(data.message || 'Registration failed', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showMessage('An error occurred. Please try again.', 'error');
            });
        });
    </script>
</body>
</html>