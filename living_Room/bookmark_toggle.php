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

if (!$room_id || !in_array($action, ['bookmark', 'unbookmark'])) {
    echo json_encode(['success' => false]);
    exit;
}

if ($action === 'bookmark') {
    $stmt = $conn->prepare("INSERT IGNORE INTO Bookmark (user_id, room_id, created_at) VALUES (?, ?, NOW())");
    $bookmarked = true;
} else {
    $stmt = $conn->prepare("DELETE FROM Bookmark WHERE user_id = ? AND room_id = ?");
    $bookmarked = false;
}

$stmt->bind_param("ii", $user_id, $room_id);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true, 'bookmarked' => $bookmarked]);
