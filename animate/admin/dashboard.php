<?php
session_start();
require_once('../config/config.php');

// Check if user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch all posts
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-dashboard">
        <header class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <nav>
                <a href="create-post.php" class="btn create-btn">Create New Post</a>
                <a href="logout.php" class="btn logout-btn">Logout</a>
            </nav>
        </header>

        <div class="posts-list">
            <h2>Manage Posts</h2>
            <div class="posts-grid">
                <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <div class="post-actions">
                        <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn edit-btn">Edit</a>
                        <a href="delete-post.php?id=<?php echo $post['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html> 