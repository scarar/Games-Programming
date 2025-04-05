#!/bin/bash

# Start the testing environment
echo "Starting Animate CMS testing environment..."

# Navigate to the testing environment directory
cd "$(dirname "$0")"

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "Docker is not installed. Please install Docker and Docker Compose."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "Docker Compose is not installed. Please install Docker Compose."
    exit 1
fi

# Start the containers
echo "Starting Docker containers..."
docker-compose up -d

# Wait for the database to be ready
echo "Waiting for the database to be ready..."
sleep 10

# Display information about the testing environment
echo ""
echo "Animate CMS Testing Environment"
echo "==============================="
echo ""
echo "Web Application: http://localhost:8080"
echo "phpMyAdmin: http://localhost:8081"
echo ""
echo "Database Information:"
echo "  Host: localhost"
echo "  Port: 3306"
echo "  Database: animate"
echo "  Username: animate_user"
echo "  Password: animate_password"
echo ""
echo "Admin Login:"
echo "  Username: admin"
echo "  Password: admin123"
echo ""
echo "To stop the testing environment, run: ./stop.sh"
echo ""

# Create a config file for the PHP application
echo "Creating config file for the PHP application..."
cat > ../animate/config/config.php << EOL
<?php
// Database configuration
define('DB_HOST', 'db');
define('DB_NAME', 'animate');
define('DB_USER', 'animate_user');
define('DB_PASS', 'animate_password');

// Site configuration
define('SITE_URL', 'http://localhost:8080');
define('ADMIN_EMAIL', 'admin@example.com');

// Security configuration
define('SECURE_COOKIES', false); // Disabled for local testing
?>
EOL

echo "Testing environment is ready!"