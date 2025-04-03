#!/bin/bash

# Database credentials
DB_USER="root"
DB_PASS=""
DB_HOST="localhost"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Animate Blog Database Setup${NC}"
echo "==============================="
echo

# Check if MySQL client is installed
if ! command -v mysql &> /dev/null; then
    echo -e "${RED}Error: MySQL client is not installed.${NC}"
    echo "Please install MySQL client and try again."
    exit 1
fi

# Prompt for password if not provided
if [ -z "$DB_PASS" ]; then
    echo -e "Using default empty password for MySQL root user."
    echo -e "If your MySQL root user has a password, press Ctrl+C and edit this script."
    echo
fi

# Run the SQL script
echo "Setting up database..."
if mysql -u"$DB_USER" -h"$DB_HOST" -p"$DB_PASS" < setup_database.sql 2>/tmp/mysql_error; then
    echo -e "${GREEN}Database setup completed successfully!${NC}"
else
    echo -e "${RED}Error setting up database:${NC}"
    cat /tmp/mysql_error
    exit 1
fi

# Verify database and tables
echo
echo "Verifying database setup..."
TABLES=("admins" "posts" "users")

for TABLE in "${TABLES[@]}"; do
    if mysql -u"$DB_USER" -h"$DB_HOST" -p"$DB_PASS" -e "USE animate; SHOW TABLES LIKE '$TABLE';" 2>/dev/null | grep -q "$TABLE"; then
        echo -e "${GREEN}Table '$TABLE' exists.${NC}"
    else
        echo -e "${RED}Warning: Table '$TABLE' does not exist!${NC}"
    fi
done

echo
echo -e "${GREEN}Setup complete!${NC}"
echo "You can now access the application at: http://localhost/animate/"
echo
echo "Default admin credentials:"
echo "Username: admin"
echo "Password: admin123"