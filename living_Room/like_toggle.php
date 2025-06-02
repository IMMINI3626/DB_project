<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];
$room_id = (int)($_POST['room_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$room_id || !in_array($action, ['like', 'unlike'])) {
    echo json_encode(['success' => false]);
    exit;
}

if ($action === 'like') {
    $stmt = $conn->prepare("INSERT IGNORE INTO Likes (user_id, room_id, created_at) VALUES (?, ?, NOW())");
    $liked = true;
} else {
    $stmt = $conn->prepare("DELETE FROM Likes WHERE user_id = ? AND room_id = ?");
    $liked = false;
}
$stmt->bind_param("ii", $user_id, $room_id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true, 'liked' => $liked]);
