<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>마이페이지</title>
</head>
<body>
<h1>마이페이지</h1>
<p>안녕하세요, <?php echo htmlspecialchars($_SESSION['user_name'] ?? '사용자'); ?>님!</p>

<ul>
    <li><a href="my_bookmarks.php">내 북마크 목록</a></li>
    <li><a href="my_reviews.php">내가 쓴 후기</a></li>
    <li><a href="rooms_list.php">자취방 전체 목록</a></li>
    <?php if ($_SESSION['user_type'] === 'admin'): ?>
        <li><a href="admin_dashboard.php">📂 관리자 페이지로</a></li>
    <?php endif; ?>
    <li><a href="withdraw.php" style="color:red;">회원 탈퇴</a></li>
    <li><a href="logout.php">로그아웃</a></li>
</ul>
</body>
</html>
