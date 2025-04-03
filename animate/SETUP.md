# Animate Blog - Setup Guide

## Quick Start

For a quick setup, simply run:

```bash
cd /workspace/Games-Programming/animate
./start_server.sh
```

Then access the website at: http://localhost:54211

## Manual Setup

### 1. Database Setup

First, ensure MariaDB/MySQL is running:

```bash
service mariadb start
```

Then set up the database:

```bash
mysql -u root < setup_database.sql
```

### 2. Start the Web Server

```bash
php -S 0.0.0.0:54211
```

### 3. Access the Website

Open your browser and navigate to:
http://localhost:54211

## Admin Access

You can log in to the admin panel with:
- Email: admin@example.com
- Password: admin123

## Troubleshooting

### Database Connection Issues

If you encounter database connection issues, verify:

1. MariaDB is running:
   ```bash
   mysqladmin ping
   ```

2. The database exists:
   ```bash
   mysql -u root -e "SHOW DATABASES;"
   ```

3. Test the connection:
   ```bash
   php test_db.php
   ```

### Web Server Issues

If the web server isn't responding:

1. Check if it's running:
   ```bash
   ps aux | grep "php -S"
   ```

2. Restart the server:
   ```bash
   killall php
   php -S 0.0.0.0:54211
   ```

## File Structure

- `index.php` - Main entry point
- `database.php` - Database connection class
- `admin/` - Admin panel files
- `css/` - Stylesheets
- `js/` - JavaScript files
- `test_db.php` - Database connection test
- `start_server.sh` - Automated setup script