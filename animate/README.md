# Animate Blog Application

## Database Setup

To set up the database for this application, you have several options:

### Option 1: Web-based Setup
1. Make sure you have MySQL server running
2. Navigate to the application directory
3. Access the setup script in your browser: `http://localhost:PORT/setup_db.php`

### Option 2: Command-line Setup (Shell Script)
1. Make sure you have MySQL server running
2. Navigate to the application directory
3. Run the setup shell script:
   ```
   ./setup_db.sh
   ```
   If needed, edit the script first to set your MySQL credentials.

### Option 3: Manual SQL Import
1. Make sure you have MySQL server running
2. Navigate to the application directory
3. Import the SQL file manually:
   ```
   mysql -u root -p < setup_database.sql
   ```

## Configuration

The application is configured to use the following database settings:
- Database name: `animate`
- Username: `root`
- Password: `` (empty)
- Host: `localhost`

If you need to change these settings, update the following files:
- `/config/database.php`
- `/database.php`

## Default Admin Account

After setting up the database, you can log in with the following credentials:
- Username: `admin`
- Password: `admin123`

## Troubleshooting

If you encounter a 500 HTTP error:
1. Make sure the database is properly set up
2. Check PHP error logs for specific error messages
3. Ensure the data directory and its subdirectories are writable
4. Verify that the MySQL server is running and accessible