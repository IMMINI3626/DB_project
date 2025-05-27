<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// POST 요청이면 탈퇴 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM User WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 세션 파기
    $_SESSION = [];
    session_destroy();

    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 탈퇴</title>
</head>
<body>
    <h1>회원 탈퇴</h1>
    <p style="color:red;">⚠️ 탈퇴 시 회원 정보와 관련 데이터가 모두 삭제됩니다.</p>
    <form method="post" onsubmit="return confirm('정말 탈퇴하시겠습니까? 이 작업은 되돌릴 수 없습니다.');">
        <button type="submit">회원 탈퇴하기</button>
    </form>
    <p><a href="mypage.php">← 마이페이지로 돌아가기</a></p>
</body>
</html>
