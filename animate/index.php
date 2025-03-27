<?php 
require_once 'config/security.php';
require_once 'includes/Post.php';

date_default_timezone_set('America/New_York');
secure_session_start();

$post = new Post();
$posts = $post->getAll(false); // Get only public posts
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <meta name="description" content="A professional blog about web development and technology">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/main-content.css">
    <link rel="stylesheet" href="css/blog.css">
    <!-- Add Font Awesome with SRI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" 
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" 
          crossorigin="anonymous">
    <!-- Add Bootstrap with SRI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
          crossorigin="anonymous">
    <!-- Add security headers -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src 'self' data: https:; font-src 'self' data: https://cdnjs.cloudflare.com; require-trusted-types-for 'script';">
    <style>
        .post-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .post-card:hover {
            transform: translateY(-2px);
        }
        .post-meta {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .youtube-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .youtube-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="loader" class="loader-container">
        <div class="container">
            <?php
                echo "<div class='loading-text'>Loading<span class='dots'></span></div>";
            ?>
            <div class="morph-animation">
                <div class="shape"></div>
            </div>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>
    </div>

    <main id="main-content" class="main-content hidden">
        <header class="site-header">
            <div class="header-container">
                <div class="logo">
                    <h1>John Doe</h1>
                    <p class="tagline">Web Developer & Tech Enthusiast</p>
                </div>
                <nav>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#blog">Blog</a></li>
                        <li><a href="#portfolio">Portfolio</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <section class="hero">
            <div class="profile-image">
                <!-- Add your profile image here -->
                <img src="images/profile.jpg" alt="John Doe">
            </div>
            <h1>Welcome to My Blog</h1>
            <p>Exploring Technology, Design, and Creative Ideas</p>
            <div class="social-links">
                <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
            </div>
        </section>

        <section class="content-wrapper">
            <div class="container mt-4">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h1 class="mb-4">Latest Posts</h1>
                    </div>
                </div>

                <div class="blog-posts">
                    <div class="posts-container">
                        <?php
                        if (empty($posts)): ?>
                            <div class="no-posts">
                                <p>No posts available at the moment.</p>
                            </div>
                        <?php else:
                            foreach ($posts as $p): ?>
                                <article class="post">
                                    <div class="post-content">
                                        <div class="post-meta">
                                            <span class="date"><?php echo date('F j, Y g:i A T', strtotime($p['created_at'])); ?></span>
                                        </div>
                                        <h2><?php echo htmlspecialchars($p['title']); ?></h2>
                                        <p><?php echo htmlspecialchars(substr($p['content'], 0, 200)) . '...'; ?></p>
                                        <a href="view_post.php?id=<?php echo $p['id']; ?>" class="read-more" target="_blank" rel="noopener noreferrer">Read More</a>
                                    </div>
                                </article>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>

                <aside class="sidebar">
                    <div class="widget about-widget">
                        <h3>About Me</h3>
                        <p>A passionate developer sharing insights about web development and design.</p>
                    </div>
                    
                    <div class="widget categories-widget">
                        <h3>Categories</h3>
                        <ul>
                            <li><a href="#">Technology</a></li>
                            <li><a href="#">Design</a></li>
                            <li><a href="#">Development</a></li>
                            <li><a href="#">Tutorials</a></li>
                        </ul>
                    </div>

                    <div class="widget recent-posts-widget">
                        <h3>Recent Posts</h3>
                        <ul>
                            <li>
                                <a href="#">Getting Started with Web Animation</a>
                                <span class="post-date"><?php echo date('F j, Y g:i A T'); ?></span>
                            </li>
                            <li>
                                <a href="#">Modern Web Design Trends</a>
                                <span class="post-date"><?php echo date('F j, Y g:i A T', strtotime('-1 day')); ?></span>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </section>
    </main>

    <div class="admin-link">
        <?php
        // Check if user is logged in as admin
        $is_admin = isset($_SESSION['user_id']) && 
                   isset($_SESSION['role']) && 
                   $_SESSION['role'] === 'admin' &&
                   isset($_SESSION['last_activity']) &&
                   isset($_SESSION['ip']) &&
                   $_SESSION['ip'] === $_SERVER['REMOTE_ADDR'] &&
                   (time() - $_SESSION['last_activity'] < 1800); // 30 minute timeout
        
        if (!$is_admin): 
            // Clear any invalid session data
            unset($_SESSION['user_id']);
            unset($_SESSION['role']);
            unset($_SESSION['last_activity']);
            unset($_SESSION['ip']);
        ?>
            <a href="admin/login.php" class="btn btn-outline-light" style="position: fixed; bottom: 20px; right: 20px; padding: 10px; background: rgba(0,0,0,0.5); color: white; text-decoration: none; border-radius: 5px;">Admin Login</a>
        <?php endif; ?>
    </div>

    <style>
        .admin-link .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            color: white;
        }
        .admin-link .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>

    <script src="js/animation.js"></script>
    <!-- Add Bootstrap JS with SRI -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
            crossorigin="anonymous"></script>
</body>
</html> 