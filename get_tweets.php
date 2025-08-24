<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$conn = new mysqli('localhost', 'uepvgtlqk6yu0', 'oqijcfrag4o1', 'dbyvflmrtu1mce');

$sql = "SELECT t.*, u.username, u.profile_pic, 
        (SELECT COUNT(*) FROM likes WHERE tweet_id = t.id) as likes,
        EXISTS(SELECT 1 FROM likes WHERE tweet_id = t.id AND user_id = ?) as user_liked,
        EXISTS(SELECT 1 FROM followers WHERE follower_id = ? AND following_id = t.user_id) as is_following
        FROM tweets t 
        JOIN users u ON t.user_id = u.id 
        ORDER BY t.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $_SESSION['user_id'], $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$tweets = [];
while ($row = $result->fetch_assoc()) {
    $tweets[] = $row;
}

echo json_encode($tweets);

$conn->close();
?> 
