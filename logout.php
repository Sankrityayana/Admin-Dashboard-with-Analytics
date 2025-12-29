<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    logActivity($_SESSION['user_id'], ACTIVITY_LOGOUT, 'User logged out');
}

session_destroy();
header('Location: login.php');
exit;
?>
