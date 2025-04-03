<?php
// Test script for admin login without restrictions
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .info-box {
            background-color: #e7f3fe;
            border-left: 6px solid #2196F3;
            padding: 10px;
            margin: 20px 0;
        }
        .success {
            background-color: #ddffdd;
            border-left: 6px solid #4CAF50;
        }
        .warning {
            background-color: #ffffcc;
            border-left: 6px solid #ffeb3b;
        }
        code {
            background-color: #f1f1f1;
            padding: 2px 5px;
            border-radius: 3px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px 0;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Admin Login Test</h1>
    
    <div class="info-box success">
        <h3>Login Restrictions Removed</h3>
        <p>All login restrictions have been successfully removed:</p>
        <ul>
            <li>Rate limiting has been disabled</li>
            <li>CSRF token verification has been disabled</li>
        </ul>
    </div>
    
    <div class="info-box warning">
        <h3>Default Admin Credentials</h3>
        <p>Email: <code>admin@example.com</code></p>
        <p>Password: <code>admin123</code></p>
    </div>
    
    <h2>Quick Actions</h2>
    <button onclick="window.location.href='admin/login.php'">Go to Admin Login</button>
    <button onclick="window.location.href='index.php'">Go to Homepage</button>
    <button onclick="window.location.href='test_db.php'">Test Database Connection</button>
    
    <h2>Changes Made</h2>
    <div class="info-box">
        <h3>1. Disabled Rate Limiting</h3>
        <p>Modified <code>config/security.php</code>:</p>
        <pre><code>// Rate limiting (DISABLED FOR TESTING)
function check_rate_limit($ip, $limit = 100, $period = 3600) {
    // Always return true to disable rate limiting
    return true;
}</code></pre>
    </div>
    
    <div class="info-box">
        <h3>2. Disabled CSRF Token Verification</h3>
        <p>Modified <code>admin/login.php</code>:</p>
        <pre><code>if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token verification disabled for testing
    // if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    //     $error = "Invalid request.";
    // } else {
        // Rest of the login code...
    // }
}</code></pre>
    </div>
    
    <h2>Security Notes</h2>
    <div class="info-box warning">
        <p><strong>Important:</strong> These security features have been disabled for testing purposes only.</p>
        <p>In a production environment, you should always enable these security features to protect against:</p>
        <ul>
            <li>Brute force attacks (rate limiting)</li>
            <li>Cross-Site Request Forgery (CSRF token verification)</li>
        </ul>
    </div>
</body>
</html>