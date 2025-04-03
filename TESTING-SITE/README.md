# Testing Site for Animate CMS

This directory contains various login options for testing the Animate CMS admin panel on a local network.

## Login Options

1. **Local Login (RECOMMENDED)**:
   - File: `local_login.php`
   - This uses modified security settings to allow cookies over HTTP

2. **Debug Login**:
   - File: `debug_login.php`
   - This provides detailed debug information about the session

3. **All Login Options**:
   - File: `all_login_options.html`
   - This page lists all available login methods with links

4. **Force Login**:
   - File: `force_login.php`
   - Forces a login by destroying any existing session and creating a new one

5. **Direct Login**:
   - File: `admin_login.php`
   - Simple direct login that sets session variables and redirects

## Issue Fixed

The main issue was that the session cookies were set to only work over HTTPS connections, but the site was being accessed over HTTP on a local network. The security settings have been modified to allow cookies over HTTP for local testing.

## Usage

1. Start the PHP server in the animate directory:
   ```
   cd /path/to/animate
   php -S 0.0.0.0:54211
   ```

2. Access the login options from your local network:
   ```
   http://[SERVER_IP]:54211/local_login.php
   ```

3. After successful login, you will be redirected to the admin panel.

## Security Note

These login methods bypass normal authentication for testing purposes. In a production environment, you should:

1. Use HTTPS for all connections
2. Enable proper authentication
3. Set secure cookie parameters
4. Implement rate limiting