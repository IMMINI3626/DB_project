<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$room_id = $_GET['room_id'] ?? null;
if (!$room_id) {
    die("잘못된 접근입니다.");
}

// 삭제 실행
$stmt = $conn->prepare("DELETE FROM Room WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$stmt->close();

header("Location: admin_rooms.php");
exit;
?>
