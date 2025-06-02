<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'fail', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$room_id = (int)($_POST['room_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$content = trim($_POST['content'] ?? '');

if ($room_id <= 0 || $rating < 1 || $rating > 5 || $content === '') {
    echo json_encode(['status' => 'fail', 'message' => 'Invalid input']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO Review (user_id, room_id, rating, content, created_at)
                        VALUES (?, ?, ?, ?, NOW())
                        ON DUPLICATE KEY UPDATE rating = VALUES(rating), content = VALUES(content), created_at = NOW()");
$stmt->bind_param("iiis", $user_id, $room_id, $rating, $content);
$stmt->execute();
$stmt->close();

echo json_encode(['status' => 'success', 'message' => 'Review submitted']);
