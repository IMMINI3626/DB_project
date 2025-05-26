<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('잘못된 접근입니다.');
}

$user_id = $_SESSION['user_id'];
$room_id = (int)($_POST['room_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$room_id || !in_array($action, ['like', 'unlike'])) {
    die('요청 파라미터 오류');
}

if ($action === 'like') {
    $stmt = $conn->prepare("INSERT IGNORE INTO LikeRoom (user_id, room_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $room_id);
    $stmt->execute();
    $stmt->close();
} elseif ($action === 'unlike') {
    $stmt = $conn->prepare("DELETE FROM LikeRoom WHERE user_id = ? AND room_id = ?");
    $stmt->bind_param("ii", $user_id, $room_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: room_detail.php?room_id=$room_id");
exit;
