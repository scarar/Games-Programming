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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = "Invalid request.";
    } else {
        try {
            // Sanitize inputs
            $title = sanitize_input($_POST['title']);
            $content = $_POST['content']; // TinyMCE already sanitizes HTML
            $is_private = isset($_POST['is_private']) ? 1 : 0;
            
            // Extract YouTube video ID if present
            $youtube_id = '';
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|music\.youtube\.com\/watch\?v=)([^"&?\/\s]{11})/', $content, $matches)) {
                $youtube_id = $matches[1];
            }

            if (empty($title)) {
                throw new Exception("Title is required.");
            }

            if (empty($content)) {
                throw new Exception("Content is required.");
            }

            if ($post->create($title, $content, $is_private, $youtube_id)) {
                $success = "Post created successfully!";
                // Clear form
                $_POST['title'] = '';
                $_POST['content'] = '';
                $_POST['is_private'] = '';
            } else {
                throw new Exception("Failed to create post. Please check file permissions.");
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Generate new CSRF token
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post</title>
    <!-- Add Bootstrap with SRI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
          crossorigin="anonymous">
    <!-- Add Font Awesome with SRI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" 
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" 
          crossorigin="anonymous">
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat | youtube',
            height: 500,
            menubar: 'file edit view insert format tools table help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
            setup: function(editor) {
                // Add YouTube button
                editor.ui.registry.addButton('youtube', {
                    text: 'YouTube',
                    icon: 'embed',
                    tooltip: 'Insert YouTube Video',
                    onAction: function() {
                        const url = prompt('Paste YouTube URL (with @ symbol):');
                        if (url) {
                            if (url.startsWith('@')) {
                                const videoUrl = url.substring(1);
                                const videoId = extractYoutubeId(videoUrl);
                                if (videoId) {
                                    const embedHtml = `<div class="youtube-container">
                                        <iframe src="https://www.youtube.com/embed/${videoId}" 
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen></iframe>
                                    </div>`;
                                    editor.insertContent(embedHtml);
                                } else {
                                    alert('Invalid YouTube URL. Please make sure to include the @ symbol and a valid YouTube URL.');
                                }
                            } else {
                                alert('Please start the URL with @ symbol');
                            }
                        }
                    }
                });

                // Function to extract YouTube ID
                function extractYoutubeId(url) {
                    const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|music\.youtube\.com\/watch\?v=)([^&\s]+)/;
                    const match = url.match(regex);
                    return match ? match[1] : null;
                }

                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    </script>
    <style>
        .tox-tinymce {
            border-radius: 5px !important;
        }
        .preview-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            display: none;
        }
        .preview-container.active {
            display: block;
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
        .form-text {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #0d6efd;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Create New Post</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form method="POST" id="postForm">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="10"></textarea>
                                <div class="form-text">
                                    YouTube videos are automatically embedded when you paste their URLs. Supported formats:<br>
                                    • https://www.youtube.com/watch?v=VIDEO_ID<br>
                                    • https://music.youtube.com/watch?v=VIDEO_ID<br>
                                    • https://youtu.be/VIDEO_ID
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_private" name="is_private">
                                    <label class="form-check-label" for="is_private">Make this post private</label>
                                </div>
                            </div>

                            <div class="preview-container" id="previewContainer">
                                <h4>Preview</h4>
                                <div id="previewContent"></div>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Post</button>
                            <a href="posts.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS with SRI -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
            crossorigin="anonymous"></script>

    <script>
        // Preview functionality
        const content = document.getElementById('content');
        const previewContainer = document.getElementById('previewContainer');
        const previewContent = document.getElementById('previewContent');

        content.addEventListener('input', function() {
            const content = tinymce.get('content').getContent();
            previewContent.innerHTML = content;
            previewContainer.classList.add('active');
        });

        // YouTube URL detection and preview
        content.addEventListener('paste', function(e) {
            const pastedData = e.clipboardData.getData('text');
            const youtubeRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
            const match = pastedData.match(youtubeRegex);
            
            if (match) {
                const videoId = match[1];
                const embedHtml = `<div class="youtube-container">
                    <iframe src="https://www.youtube.com/embed/${videoId}" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>
                </div>`;
                
                tinymce.get('content').insertContent(embedHtml);
            }
        });
    </script>
</body>
</html> 