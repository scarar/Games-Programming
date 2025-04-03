<?php
// Debug login script - provides detailed information about the login process

// 1. Clear any existing session
if (session_status() !== PHP_SESSION_NONE) {
    session_destroy();
}

// 2. Start a new session with basic settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
// Disable secure cookie for local testing
ini_set('session.cookie_secure', 0);
ini_set('session.cookie_samesite', 'Lax');
session_start();

// 3. Set session variables
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';
$_SESSION['last_activity'] = time();
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

// 4. Output debug information
echo "<!DOCTYPE html>
<html>
<head>
    <title>Debug Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug-info { background: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .success { color: green; }
        .error { color: red; }
        .button { 
            display: inline-block; 
            background: #4CAF50; 
            color: white; 
            padding: 10px 15px; 
            text-decoration: none; 
            border-radius: 4px; 
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Debug Login</h1>
    
    <div class='debug-info'>
        <h2>Session Information</h2>
        <p><strong>Session ID:</strong> " . session_id() . "</p>
        <p><strong>Session Status:</strong> " . (session_status() === PHP_SESSION_ACTIVE ? "<span class='success'>Active</span>" : "<span class='error'>Inactive</span>") . "</p>
        <p><strong>Session Variables:</strong></p>
        <ul>
            <li>user_id: " . $_SESSION['user_id'] . "</li>
            <li>role: " . $_SESSION['role'] . "</li>
            <li>last_activity: " . date('Y-m-d H:i:s', $_SESSION['last_activity']) . "</li>
            <li>ip: " . $_SESSION['ip'] . "</li>
        </ul>
    </div>
    
    <div class='debug-info'>
        <h2>Server Information</h2>
        <p><strong>Server IP:</strong> " . $_SERVER['SERVER_ADDR'] . "</p>
        <p><strong>Client IP:</strong> " . $_SERVER['REMOTE_ADDR'] . "</p>
        <p><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>
        <p><strong>PHP Version:</strong> " . phpversion() . "</p>
    </div>
    
    <div class='debug-info'>
        <h2>Cookie Information</h2>
        <p><strong>Session Cookie Name:</strong> " . session_name() . "</p>
        <p><strong>Session Cookie Parameters:</strong></p>
        <ul>";
        $params = session_get_cookie_params();
        foreach ($params as $key => $value) {
            echo "<li>$key: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value) . "</li>";
        }
echo "
        </ul>
    </div>
    
    <h2>Next Steps</h2>
    <p>Session variables have been set. You should now be able to access the admin panel.</p>
    <p><a href='admin/posts.php' class='button'>Go to Admin Panel</a></p>
    
    <p>If the above link doesn't work, try these alternatives:</p>
    <p><a href='force_login.php' class='button'>Force Login</a></p>
    <p><a href='admin_login.php' class='button'>Admin Login</a></p>
</body>
</html>";
?>