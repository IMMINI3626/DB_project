<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Living Alone - 메인</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <div class="container">
    <h1>대학생 자취방 정보 관리 시스템</h1>
    <ul class="nav-menu">
      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="mypage.php">마이페이지</a></li>
        <li><a href="logout.php">로그아웃</a></li>
      <?php else: ?>
        <li><a href="join_user.php">회원가입</a></li>
        <li><a href="login.php">로그인</a></li>
      <?php endif; ?>
      <li><a href="rooms_list.php">자취방 목록 보기</a></li>
    </ul>
  </div>
</body>
</html>
