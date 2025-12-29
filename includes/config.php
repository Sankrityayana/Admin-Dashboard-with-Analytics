<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'admin_dashboard');

// Site configuration
define('SITE_NAME', 'Admin Dashboard Pro');
define('SITE_TITLE', 'Analytics & Management System');
define('SITE_EMAIL', 'admin@dashboard.com');
define('TIMEZONE', 'UTC');

// Role constants
define('ROLE_SUPER_ADMIN', 'super_admin');
define('ROLE_ADMIN', 'admin');
define('ROLE_MODERATOR', 'moderator');
define('ROLE_USER', 'user');

// Status constants
define('STATUS_ACTIVE', 'active');
define('STATUS_INACTIVE', 'inactive');
define('STATUS_SUSPENDED', 'suspended');

// Activity types
define('ACTIVITY_LOGIN', 'login');
define('ACTIVITY_LOGOUT', 'logout');
define('ACTIVITY_CREATE', 'create');
define('ACTIVITY_UPDATE', 'update');
define('ACTIVITY_DELETE', 'delete');
define('ACTIVITY_VIEW', 'view');
define('ACTIVITY_EXPORT', 'export');
define('ACTIVITY_IMPORT', 'import');
define('ACTIVITY_SYSTEM', 'system');

// Pagination
define('RECORDS_PER_PAGE', 15);

// Create database connection
$conn = mysqli_connect(DB_HOST . ':' . DB_PORT, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, 'utf8mb4');

// Set timezone
date_default_timezone_set(TIMEZONE);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
