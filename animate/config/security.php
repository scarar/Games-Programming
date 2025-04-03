<?php
// Security Configuration

// Set secure session parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Disabled for local testing (should be 1 in production)
ini_set('session.cookie_samesite', 'Lax'); // Less strict for local testing

// Set secure headers
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https://cdn.jsdelivr.net; style-src \'self\' \'unsafe-inline\' https://cdn.jsdelivr.net; img-src \'self\' data: https:; font-src \'self\' https://cdnjs.cloudflare.com;');

// Security functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Rate limiting
function check_rate_limit($ip, $limit = 100, $period = 3600) {
    $file = sys_get_temp_dir() . '/rate_limit_' . md5($ip);
    $current = file_exists($file) ? (int)file_get_contents($file) : 0;
    
    if ($current >= $limit) {
        return false;
    }
    
    file_put_contents($file, $current + 1);
    return true;
}

// Password hashing
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Session security
function secure_session_start() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) {
        // Regenerate session ID every 30 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

// Error handling
function handle_error($errno, $errstr, $errfile, $errline) {
    error_log("Error [$errno] $errstr in $errfile on line $errline");
    return true;
}

set_error_handler('handle_error');

// Prevent direct access to this file
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
    exit('No direct access allowed.');
}
?> 