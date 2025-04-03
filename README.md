# Animate CMS

This branch contains only the Animate CMS project, a simple content management system for creating and managing blog posts.

## Features

- Admin panel for managing posts
- Responsive design
- Animation effects
- Database integration

## Login Information

- Username: admin
- Password: admin123

## Local Development

To run the project locally:

1. Start the PHP server:
   ```
   cd animate
   php -S 0.0.0.0:54211
   ```

2. Access the site:
   ```
   http://localhost:54211
   ```

3. Admin panel:
   ```
   http://localhost:54211/admin/login.php
   ```

## Login Issues on Local Network

If you're having issues logging in over HTTP on a local network, use one of these solutions:

1. **Local Login**: http://[SERVER_IP]:54211/local_login.php
2. **Debug Login**: http://[SERVER_IP]:54211/debug_login.php
3. **Direct Login**: http://[SERVER_IP]:54211/admin_login.php

These login methods bypass normal authentication for testing purposes.