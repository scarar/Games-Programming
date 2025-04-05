#!/bin/bash

# Stop the testing environment
echo "Stopping Animate CMS testing environment..."

# Navigate to the testing environment directory
cd "$(dirname "$0")"

# Stop the containers
docker-compose down

echo "Testing environment stopped."