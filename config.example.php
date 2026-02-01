<?php
// Copy this file to config.php and fill values before running locally or on the server.

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'portfolio_db');

// Email Configuration
define('ADMIN_EMAIL', 'you@example.com'); // Change this to your email
define('ADMIN_NAME', 'Your Name');

// Site Configuration
define('SITE_URL', 'https://yourdomain.com/');
define('SITE_NAME', 'Your Portfolio');

// Timezone
date_default_timezone_set('Asia/Colombo');

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

?>
