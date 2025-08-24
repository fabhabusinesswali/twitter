<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Get POST data
$content = isset($_POST['content']) ? $_POST['content'] : '';

if (empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Tweet content cannot be empty']);
    exit();
}

$conn = new mysqli('localhost', 'uepvgtlqk6yu0', 'oqijcfrag4o1', 'dbyvflmrtu1mce');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit();
}

// Prepare and execute the query
$stmt = $conn->prepare("INSERT INTO tweets (user_id, content) VALUES (?, ?)");
$stmt->bind_param("is", $_SESSION['user_id'], $content);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to post tweet']);
}

$stmt->close();
$conn->close();
?> 
