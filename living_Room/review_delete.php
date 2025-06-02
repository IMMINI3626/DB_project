<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('잘못된 접근입니다.');
}

$user_id = $_SESSION['user_id'];
$room_id = (int)($_POST['room_id'] ?? 0);

if ($room_id <= 0) {
    die('잘못된 방 ID');
}

$stmt = $conn->prepare("DELETE FROM Review WHERE user_id = ? AND room_id = ?");
$stmt->bind_param("ii", $user_id, $room_id);
$stmt->execute();
$stmt->close();

header("Location: room_detail.php?room_id=$room_id");
exit;
