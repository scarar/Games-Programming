<?php
session_start();
require_once('../config/database.php');
require_once('../config/config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $youtube_url = $_POST['youtube_url'];
    
    // Handle image upload
    $image_url = $_POST['current_image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/posts/";
        $image_url = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_url);
    }
    
    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, category = ?, image_url = ?, youtube_url = ? WHERE id = ?");
    $stmt->execute([$title, $content, $category, $image_url, $youtube_url, $id]);
    
    header('Location: dashboard.php');
    exit();
}

// Get existing post data
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-dashboard">
        <h2>Edit Post</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($post['category']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image">
                <?php if ($post['image_url']): ?>
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($post['image_url']); ?>">
                    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" style="max-width: 200px; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="youtube_url">YouTube URL</label>
                <input type="text" id="youtube_url" name="youtube_url" value="<?php echo htmlspecialchars($post['youtube_url']); ?>">
            </div>
            <button type="submit">Update Post</button>
            <a href="dashboard.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html> 