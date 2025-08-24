<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include the same header and styling as home.php
// Add notification-specific content
?> 
