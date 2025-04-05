#!/bin/bash

# Run all tests for the Animate CMS project
echo "Running tests for Animate CMS..."

# Navigate to the testing environment directory
cd "$(dirname "$0")"

# Test PHP syntax
echo "Testing PHP syntax..."
./test_syntax.sh
if [ $? -ne 0 ]; then
    echo "PHP syntax test failed."
    exit 1
fi
echo "PHP syntax test passed."
echo ""

# Start the testing environment if it's not already running
if ! docker ps | grep -q animate_web; then
    echo "Starting testing environment..."
    ./start.sh
    # Wait for the environment to be ready
    sleep 10
fi

# Run database tests
echo "Testing database connection..."
docker exec animate_web php -f /var/www/html/test_db.php
if [ $? -ne 0 ]; then
    echo "Database connection test failed."
    exit 1
fi
echo "Database connection test passed."
echo ""

# Run login tests
echo "Testing admin login..."
docker exec animate_web php -f /var/www/html/test_login.php
if [ $? -ne 0 ]; then
    echo "Admin login test failed."
    exit 1
fi
echo "Admin login test passed."
echo ""

echo "All tests passed!"