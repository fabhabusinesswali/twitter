<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'uepvgtlqk6yu0', 'oqijcfrag4o1', 'dbyvflmrtu1mce');
$profile_username = isset($_GET['username']) ? $_GET['username'] : $_SESSION['username'];

// Get user profile data
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $profile_username);
$stmt->execute();
$result = $stmt->get_result();
$profile_user = $result->fetch_assoc();

// Get user's tweets
$stmt = $conn->prepare("SELECT * FROM tweets WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $profile_user['id']);
$stmt->execute();
$tweets = $stmt->get_result();

// Include the same header and styling as home.php
// Add profile-specific content
?> 
