<?php
// Local login with modified security settings

// Disable secure cookie requirement for local testing
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0);  // Allow cookies over HTTP
ini_set('session.cookie_samesite', 'Lax');  // Less strict SameSite policy

// Start a new session
session_start();

// Set session variables
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';
$_SESSION['last_activity'] = time();
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

// Output success message
echo "<!DOCTYPE html>
<html>
<head>
    <title>Local Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
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
    <h1>Local Login</h1>
    
    <p class='success'>Login successful! Session variables have been set with modified security settings.</p>
    
    <p>You should now be able to access the admin panel:</p>
    <p><a href='admin/posts.php' class='button'>Go to Admin Panel</a></p>
    
    <p>If you're still having issues, please try:</p>
    <ol>
        <li>Clearing your browser cookies</li>
        <li>Using a private/incognito window</li>
        <li>Using a different browser</li>
    </ol>
</body>
</html>";
?>