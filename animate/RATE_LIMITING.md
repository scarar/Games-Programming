# Rate Limiting for Admin Login

## Current Status

Rate limiting has been **disabled** for testing purposes. This allows unlimited login attempts without being locked out.

## How to Re-enable Rate Limiting

When you're done testing and want to restore the security feature, follow these steps:

### 1. Edit `config/security.php`

Restore the original rate limiting function:

```php
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
```

### 2. Edit `admin/login.php`

Restore the rate limiting check:

```php
// Check rate limiting
if (!check_rate_limit($_SERVER['REMOTE_ADDR'], 5, 300)) { // 5 attempts per 5 minutes
    $error = "Too many login attempts. Please try again later.";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
```

## Security Considerations

Rate limiting is an important security feature that helps prevent brute force attacks on your login system. It should always be enabled in production environments.

The current settings limit login attempts to:
- 5 attempts per 5 minutes (300 seconds) for the admin login
- 100 attempts per hour (3600 seconds) for other rate-limited features

## Testing the Rate Limiting

After re-enabling rate limiting, you can test it by:
1. Attempting to log in with incorrect credentials multiple times
2. After 5 failed attempts, you should see the message: "Too many login attempts. Please try again later."
3. Wait 5 minutes for the rate limit to reset

## Additional Security Recommendations

For production environments, consider:
1. Implementing more sophisticated rate limiting based on both IP and username
2. Adding CAPTCHA after a certain number of failed attempts
3. Implementing account lockout policies for repeated failures
4. Using a more secure password hashing algorithm with proper salting