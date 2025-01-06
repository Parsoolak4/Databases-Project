<?php
// Define the base directory of the project
define('BASE_DIR', __DIR__.'/../'); // Adjusted to point to the root directory of the project

// Define the URL of the project (adjust as needed)
define('BASE_URL', 'http://comp353.local/');

// Database configuration settings (adjust as needed)
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');

// Error reporting settings (enable for development, disable for production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
