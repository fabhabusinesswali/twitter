<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$follower_id = $_SESSION['user_id'];
$following_id = $data['follow_id'];

$conn = new mysqli('localhost', 'uepvgtlqk6yu0', 'oqijcfrag4o1', 'dbyvflmrtu1mce');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit();
}

// Check if already following
$check_sql = "SELECT * FROM followers WHERE follower_id = ? AND following_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $follower_id, $following_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Unfollow
    $sql = "DELETE FROM followers WHERE follower_id = ? AND following_id = ?";
    $following = false;
} else {
    // Follow
    $sql = "INSERT INTO followers (follower_id, following_id) VALUES (?, ?)";
    $following = true;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $follower_id, $following_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'following' => $following]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update follow status']);
}

$conn->close();
?> 
