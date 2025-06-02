<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('잘못된 접근입니다.');
}

$user_id = $_SESSION['user_id'];
$room_id = (int)($_POST['room_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$content = trim($_POST['content'] ?? '');

if ($room_id <= 0 || $rating < 1 || $rating > 5 || $content === '') {
    die('입력 값 오류');
}

$stmt = $conn->prepare("UPDATE Review SET rating = ?, content = ?, created_at = NOW() WHERE user_id = ? AND room_id = ?");
$stmt->bind_param("isii", $rating, $content, $user_id, $room_id);
$stmt->execute();
$stmt->close();

header("Location: room_detail.php?room_id=$room_id");
exit;
