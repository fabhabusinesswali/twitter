<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$conn = new mysqli('localhost', 'uepvgtlqk6yu0', 'oqijcfrag4o1', 'dbyvflmrtu1mce');

$tweet_id = $data['tweet_id'];
$user_id = $_SESSION['user_id'];

// Check if already liked
$check_sql = "SELECT * FROM likes WHERE user_id = ? AND tweet_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $user_id, $tweet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Unlike
    $sql = "DELETE FROM likes WHERE user_id = ? AND tweet_id = ?";
} else {
    // Like
    $sql = "INSERT INTO likes (user_id, tweet_id) VALUES (?, ?)";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $tweet_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update like']);
}

$conn->close();
?> 
