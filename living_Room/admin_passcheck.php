<?php
session_start();

$pass_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_pass = $_POST['admin_pass'] ?? '';

    // 4자리 비밀번호 설정 (예: 1234)
    $correct_pass = '1234';

    if ($input_pass === $correct_pass) {
        $_SESSION['admin_pass_verified'] = true;
        header('Location: admin_login.php');
        exit;
    } else {
        $pass_error = '비밀번호가 틀렸습니다.';
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>관리자 인증</title>
</head>
<body>
    <h1>관리자 인증</h1>
    <?php if ($pass_error): ?>
        <p style="color:red;"><?= htmlspecialchars($pass_error) ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <input type="password" name="admin_pass" maxlength="4" placeholder="4자리 비밀번호 입력" required autofocus>
        <button type="submit">확인</button>
    </form>
</body>
</html>
