<?php
require_once 'common/config.php';

// Destroy session
session_destroy();

// Redirect to login page
redirect('login.php');
?>