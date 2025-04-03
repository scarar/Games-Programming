<?php
// This is a network-accessible login script without any security checks
session_start();

// Log the request for debugging
error_log("Network login accessed from IP: " . $_SERVER['REMOTE_ADDR']);

// Set session variables directly (no authentication)
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';
$_SESSION['last_activity'] = time();
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

// Redirect to admin panel
header('Location: admin/posts.php');
exit();
?>