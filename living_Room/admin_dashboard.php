<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>관리자 대시보드</title></head>
<body>
<h1>관리자 페이지</h1>
<p>안녕하세요, 관리자 <?php echo htmlspecialchars($_SESSION['user_name']); ?>님</p>
<ul>
    <li><a href="admin_rooms.php">자취방 관리</a></li>
    <li><a href="admin_features.php">옵션 목록 관리</a></li>
    <li><a href="admin_reviews.php">후기 관리</a></li>
    <li><a href="admin_users.php">사용자 관리</a></li>
    <li><a href="logout.php">로그아웃</a></li>
</ul>
</body></html>
