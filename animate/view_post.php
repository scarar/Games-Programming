<?php
require_once 'config/security.php';
require_once 'includes/Post.php';

secure_session_start();

$post = new Post();
$error = '';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$post_data = $post->getById($_GET['id']);

if (!$post_data) {
    header('Location: index.php');
    exit();
}

// Check if user has access to private posts
if ($post_data['is_private'] && (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin')) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post_data['title']); ?></title>
    <!-- Add Bootstrap with SRI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
          crossorigin="anonymous">
    <!-- Add Font Awesome with SRI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" 
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" 
          crossorigin="anonymous">
    <style>
        .post-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }
        .youtube-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin: 2rem 0;
        }
        .youtube-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .post-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .post-title {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <article class="card">
                    <div class="card-body">
                        <h1 class="post-title"><?php echo htmlspecialchars($post_data['title']); ?></h1>
                        
                        <div class="post-meta mb-4">
                            <i class="far fa-calendar-alt me-2"></i>
                            <?php echo date('F j, Y', strtotime($post_data['created_at'])); ?>
                        </div>

                        <?php if ($post_data['youtube_id']): ?>
                            <div class="youtube-container">
                                <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($post_data['youtube_id']); ?>" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <?php echo $post_data['content']; ?>
                        </div>
                    </div>
                </article>

                <div class="mt-4">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Posts
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS with SRI -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
            crossorigin="anonymous"></script>
</body>
</html> 