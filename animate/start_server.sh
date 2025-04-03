#!/bin/bash

# Start MariaDB if not running
if ! mysqladmin ping &>/dev/null; then
    echo "Starting MariaDB..."
    service mariadb start
    sleep 2
fi

# Verify database exists
if ! mysql -u root -e "USE animate;" &>/dev/null; then
    echo "Setting up database..."
    mysql -u root < setup_database.sql
fi

# Start PHP server
echo "Starting PHP server on port 54211..."
php -S 0.0.0.0:54211 > /dev/null 2>&1 &

echo "Server started! Access the website at: http://localhost:54211"
echo "Admin login: admin@example.com / admin123"