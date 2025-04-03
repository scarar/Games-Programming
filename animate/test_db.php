<?php
// Test database connection
$host = 'localhost';
$dbname = 'animate';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test query
    $stmt = $pdo->query("SELECT COUNT(*) as post_count FROM posts");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Database connection successful!<br>";
    echo "Number of posts in database: " . $result['post_count'] . "<br>";
    
    // Test admin table
    $stmt = $pdo->query("SELECT COUNT(*) as admin_count FROM admins");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Number of admins in database: " . $result['admin_count'] . "<br>";
    
    // Test users table
    $stmt = $pdo->query("SELECT COUNT(*) as user_count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Number of users in database: " . $result['user_count'] . "<br>";
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>