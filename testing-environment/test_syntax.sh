#!/bin/bash

# Test PHP syntax in the animate directory
echo "Testing PHP syntax in the animate directory..."

# Navigate to the parent directory
cd "$(dirname "$0")/.."

# Find all PHP files and check their syntax
echo "Checking PHP syntax..."
find animate -name "*.php" -type f -print0 | while IFS= read -r -d '' file; do
    php -l "$file" > /dev/null
    if [ $? -ne 0 ]; then
        echo "Syntax error in $file"
        exit 1
    fi
done

echo "All PHP files have valid syntax."