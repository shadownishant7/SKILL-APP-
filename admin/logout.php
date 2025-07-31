<?php
require_once '../common/config.php';

// Destroy admin session
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

// Redirect to admin login
redirect('login.php');
?>