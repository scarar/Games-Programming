# Animate CMS Testing Environment

This directory contains a Docker-based testing environment for the Animate CMS project. It includes:

- PHP 8.0 with Apache
- MySQL 8.0
- phpMyAdmin
- Pre-configured database with test data

## Requirements

- Docker
- Docker Compose

## Getting Started

1. Make sure Docker and Docker Compose are installed on your system.

2. Start the testing environment:

   ```bash
   chmod +x start.sh
   ./start.sh
   ```

3. Access the web application:

   - Web Application: http://localhost:8080
   - phpMyAdmin: http://localhost:8081

4. Log in to the admin panel:

   - URL: http://localhost:8080/admin/login.php
   - Username: admin
   - Password: admin123

5. To stop the testing environment:

   ```bash
   chmod +x stop.sh
   ./stop.sh
   ```

## Database Information

- Host: localhost
- Port: 3306
- Database: animate
- Username: animate_user
- Password: animate_password

## Directory Structure

- `docker-compose.yml`: Docker Compose configuration
- `init-db/`: Database initialization scripts
  - `01-schema.sql`: Database schema
  - `02-test-data.sql`: Test data
- `start.sh`: Script to start the testing environment
- `stop.sh`: Script to stop the testing environment

## Troubleshooting

### Login Issues

If you're having issues logging in to the admin panel, try:

1. Using the direct login methods in the TESTING-SITE directory:
   - http://localhost:8080/TESTING-SITE/local_login.php
   - http://localhost:8080/TESTING-SITE/debug_login.php
   - http://localhost:8080/TESTING-SITE/admin_login.php

2. Checking the logs:
   ```bash
   docker logs animate_web
   docker logs animate_db
   ```

### Database Connection Issues

If the application can't connect to the database:

1. Make sure the containers are running:
   ```bash
   docker-compose ps
   ```

2. Check if the database is initialized properly:
   - Access phpMyAdmin at http://localhost:8081
   - Log in with username `animate_user` and password `animate_password`
   - Check if the `animate` database and its tables exist

3. Verify the database configuration in `../animate/config/config.php`