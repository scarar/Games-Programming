<?php
// Test script for admin login without rate limiting
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
    
    <div class="info-box">
        <h3>Rate Limiting Status</h3>
        <p>Rate limiting has been <strong>disabled</strong> for testing purposes.</p>
        <p>You can now log in multiple times without being locked out.</p>
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
    
    <h2>Security Notes</h2>
    <div class="info-box warning">
        <p><strong>Important:</strong> Rate limiting has been disabled for testing purposes only.</p>
        <p>In a production environment, you should always enable rate limiting to prevent brute force attacks.</p>
    </div>
    
    <h2>Modified Files</h2>
    <ul>
        <li><code>config/security.php</code> - Disabled rate limiting function</li>
        <li><code>admin/login.php</code> - Commented out rate limiting check</li>
    </ul>
</body>
</html>