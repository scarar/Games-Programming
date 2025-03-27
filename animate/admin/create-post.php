<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $youtube_url = $_POST['youtube_url'];
    
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/posts/";
        $image_url = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_url);
    }
    
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, category, image_url, youtube_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $content, $category, $image_url, $youtube_url]);
    
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-dashboard">
        <h2>Create New Post</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" required></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" required>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image">
            </div>
            <div class="form-group">
                <label for="youtube_url">YouTube URL</label>
                <input type="text" id="youtube_url" name="youtube_url">
            </div>
            <button type="submit">Create Post</button>
        </form>
    </div>
</body>
</html> 