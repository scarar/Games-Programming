<?php
require_once '../config/security.php';
secure_session_start();

// If already logged in, redirect to posts
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    header('Location: posts.php');
    exit();
}

// Rate limiting disabled for testing
// if (!check_rate_limit($_SERVER['REMOTE_ADDR'], 5, 300)) { // 5 attempts per 5 minutes
//     $error = "Too many login attempts. Please try again later.";
// } else 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token verification disabled for testing
    // if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    //     $error = "Invalid request.";
    // } else {
        // Sanitize inputs
        $username = sanitize_input($_POST['email']); // Using email field for username
        $password = $_POST['password'];

        // Include database connection
        require_once '../database.php';
        $database = new Database();
        $conn = $database->getConnection();
        
        if ($conn) {
            try {
                // Query the admin table
                $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
                $stmt->execute([$username]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($admin) {
                    // For testing purposes, bypass password verification
                    $passwordValid = true;
                    
                    // In production, you would verify the password hash:
                    // $passwordValid = password_verify($password, $admin['password']);
                    
                    if ($passwordValid) {
                        // Regenerate session ID for security
                        session_regenerate_id(true);
                        
                        // Set session variables
                        $_SESSION['user_id'] = $admin['id'];
                        $_SESSION['role'] = 'admin';
                        $_SESSION['last_activity'] = time();
                        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                        
                        // Log successful login
                        error_log("Successful admin login from IP: " . $_SERVER['REMOTE_ADDR']);
                        
                        header('Location: posts.php');
                        exit();
                    } else {
                        $error = "Invalid password";
                    }
                } else {
                    $error = "Admin user not found";
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        } else {
            $error = "Database connection failed";
        }
    // }
}

// Generate new CSRF token
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Add Bootstrap with SRI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
          crossorigin="anonymous">
    <!-- Add Font Awesome with SRI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" 
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" 
          crossorigin="anonymous">
    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            width: 90%;
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: #007bff;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1.5rem;
        }
        .card-header h3 {
            margin: 0;
            font-size: 1.5rem;
        }
        .card-body {
            padding: 2rem;
        }
        .form-control {
            border-radius: 5px;
            padding: 0.75rem;
        }
        .btn-primary {
            padding: 0.75rem;
            font-weight: 500;
        }
        .back-to-site {
            position: fixed;
            top: 20px;
            left: 20px;
            color: #6c757d;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .back-to-site:hover {
            color: #007bff;
        }
        .alert {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <a href="../index.php" class="back-to-site">
        <i class="fas fa-arrow-left"></i> Back to Site
    </a>
    
    <div class="login-container">
        <div class="card">
            <div class="card-header text-center">
                <h3><i class="fas fa-lock me-2"></i>Admin Login</h3>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="email" name="email" required 
                                   placeholder="Enter your username" value="admin" autocomplete="username">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required 
                                   placeholder="Enter your password" value="admin123" autocomplete="current-password">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS with SRI -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
            crossorigin="anonymous"></script>
</body>
</html> 