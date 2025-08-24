<?php
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$conn = new mysqli('localhost', 'uepvgtlqk6yu0', 'oqijcfrag4o1', 'dbyvflmrtu1mce');

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed']));
}

$username = $conn->real_escape_string($data['username']);
$email = $conn->real_escape_string($data['email']);
$password = password_hash($data['password'], PASSWORD_DEFAULT);

$check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
$result = $conn->query($check_query);

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
    exit();
}

$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

if ($conn->query($sql)) {
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['username'] = $username;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed']);
}

$conn->close();
?> 
