<?php
// Force login script - bypasses all security checks
// Start a clean session
if (session_status() !== PHP_SESSION_NONE) {
    session_destroy();
}
session_start();

// Set session variables directly
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';
$_SESSION['last_activity'] = time();
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

// Debug information
echo "Session variables set:<br>";
echo "user_id: " . $_SESSION['user_id'] . "<br>";
echo "role: " . $_SESSION['role'] . "<br>";
echo "last_activity: " . $_SESSION['last_activity'] . "<br>";
echo "ip: " . $_SESSION['ip'] . "<br>";
echo "<br>Redirecting to admin panel in 3 seconds...";

// JavaScript redirect after a delay
echo "<script>
setTimeout(function() {
    window.location.href = '../animate/admin/posts.php';
}, 3000);
</script>";
?>