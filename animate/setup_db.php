<?php
// Database setup script
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Create PDO connection without database name
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Setup</h2>";
    
    // Read SQL file
    $sql_file = file_get_contents('setup_database.sql');
    
    // Split SQL file into individual statements
    $statements = array_filter(
        array_map('trim', 
            explode(';', $sql_file)
        )
    );
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                echo "<p>Success: " . htmlspecialchars(substr($statement, 0, 50)) . "...</p>";
                
                // After creating and selecting the database, reconnect to it
                if (stripos($statement, 'USE animate') !== false) {
                    $pdo = new PDO("mysql:host=$host;dbname=animate", $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    echo "<p>Connected to the animate database</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error: " . $e->getMessage() . "<br>Statement: " . htmlspecialchars(substr($statement, 0, 100)) . "...</p>";
            }
        }
    }
    
    // Verify database and tables exist
    try {
        $check_pdo = new PDO("mysql:host=$host;dbname=animate", $username, $password);
        $check_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $tables = ['admins', 'posts', 'users'];
        $all_tables_exist = true;
        
        foreach ($tables as $table) {
            $stmt = $check_pdo->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() == 0) {
                echo "<p>Warning: Table '$table' does not exist!</p>";
                $all_tables_exist = false;
            } else {
                echo "<p>Table '$table' exists.</p>";
            }
        }
        
        if ($all_tables_exist) {
            echo "<h3>Database setup completed successfully!</h3>";
        } else {
            echo "<h3>Database setup completed with warnings.</h3>";
        }
    } catch (PDOException $e) {
        echo "<h3>Database setup may have failed.</h3>";
        echo "<p>Error verifying database: " . $e->getMessage() . "</p>";
    }
    
    echo "<p><a href='index.php'>Go to homepage</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>Database Connection Error</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure your MySQL server is running and the credentials are correct.</p>";
}
?>