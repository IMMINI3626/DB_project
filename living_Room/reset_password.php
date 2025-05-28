<?php
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = '이메일을 입력해주세요.';
    } else {
        // TODO: 이메일 유효성 체크, DB에 존재 여부 확인
        // TODO: 비밀번호 재설정 토큰 생성 후 이메일 발송 기능 구현

        // 여기서는 간단히 성공 메시지 출력
        $success = '비밀번호 재설정 링크가 이메일로 발송되었습니다.';
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>비밀번호 재설정</title>
</head>
<body>
    <h1>비밀번호 재설정</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="email" name="email" placeholder="등록된 이메일을 입력하세요" required>
        <button type="submit">재설정 링크 받기</button>
    </form>

    <p><a href="login.php">로그인 페이지로 돌아가기</a></p>
</body>
</html>
