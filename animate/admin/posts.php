<?php
require_once '../config/security.php';
require_once '../includes/Post.php';

secure_session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$post = new Post();
$error = '';
$success = '';

// Handle post actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = "Invalid request.";
    } else {
        $action = $_POST['action'] ?? '';
        $post_id = $_POST['post_id'] ?? '';

        switch ($action) {
            case 'delete':
                if ($post->delete($post_id)) {
                    $success = "Post deleted successfully.";
                } else {
                    $error = "Failed to delete post.";
                }
                break;

            case 'toggle_visibility':
                if ($post->toggleVisibility($post_id)) {
                    $success = "Post visibility updated successfully.";
                } else {
                    $error = "Failed to update post visibility.";
                }
                break;
        }
    }
}

// Get all posts (including private ones for admin)
$posts = $post->getAll(true);

// Generate new CSRF token
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <!-- Add Bootstrap with SRI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
          crossorigin="anonymous">
    <!-- Add Font Awesome with SRI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" 
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" 
          crossorigin="anonymous">
    <style>
        .post-card {
            transition: transform 0.2s;
        }
        .post-card:hover {
            transform: translateY(-2px);
        }
        .private-badge {
            font-size: 0.8rem;
        }
        .action-buttons .btn {
            margin-right: 0.5rem;
        }
        .post-meta {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Manage Posts</h2>
                    <a href="post.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Post
                    </a>
                </div>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="row">
            <?php if (empty($posts)): ?>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        No posts found. <a href="post.php">Create your first post</a>.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post_data): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card post-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">
                                        <?php echo htmlspecialchars($post_data['title']); ?>
                                        <?php if ($post_data['is_private']): ?>
                                            <span class="badge bg-warning text-dark private-badge">Private</span>
                                        <?php endif; ?>
                                    </h5>
                                    <div class="action-buttons">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                            <input type="hidden" name="post_id" value="<?php echo $post_data['id']; ?>">
                                            <input type="hidden" name="action" value="toggle_visibility">
                                            <button type="submit" class="btn btn-sm <?php echo $post_data['is_private'] ? 'btn-success' : 'btn-warning'; ?>">
                                                <i class="fas <?php echo $post_data['is_private'] ? 'fa-eye' : 'fa-eye-slash'; ?>"></i>
                                            </button>
                                        </form>
                                        <a href="edit_post.php?id=<?php echo $post_data['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                            <input type="hidden" name="post_id" value="<?php echo $post_data['id']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="post-meta mb-2">
                                    <i class="far fa-calendar-alt me-2"></i>
                                    <?php echo date('F j, Y', strtotime($post_data['created_at'])); ?>
                                </div>
                                <p class="card-text">
                                    <?php 
                                    $content = strip_tags($post_data['content']);
                                    echo substr($content, 0, 150) . (strlen($content) > 150 ? '...' : '');
                                    ?>
                                </p>
                                <a href="../view_post.php?id=<?php echo $post_data['id']; ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i>View Post
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Bootstrap JS with SRI -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
            crossorigin="anonymous"></script>
</body>
</html> 