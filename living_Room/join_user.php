<?php
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim($_POST['name'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $user_type = 'user';

    if (empty($name) || empty($phone) || empty($email) || empty($password)) {
        $error = '모든 필드를 입력해주세요.';
    } else {
        $checkStmt = $conn->prepare("SELECT user_id FROM User WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result && $result->num_rows > 0) {
            $error = '이미 등록된 이메일입니다.';
        } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/', $password)) {
            $error = '비밀번호 입력 방식을 다시 확인해주세요.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertStmt = $conn->prepare(
                "INSERT INTO User (name, phone, email, password, user_type, created_at)
                 VALUES (?, ?, ?, ?, ?, NOW())"
            );
            $insertStmt->bind_param("sssss", $name, $phone, $email, $hashedPassword, $user_type);

            if ($insertStmt->execute()) {
                $insertStmt->close();
                header('Location: login.php');
                exit;
            } else {
                $error = '회원가입 실패: ' . $insertStmt->error;
            }
        }
        $checkStmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="css/join_user.css">
    <meta charset="UTF-8">
    <title>회원가입</title>
</head>
<body>
<div class="join_use-container">
    <h1>회원가입</h1>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <input type="text" name="name" placeholder="이름을 입력하세요" required><br>
        <input type="email" name="email" placeholder="이메일을 입력하세요" required><br>
        <input type="text" name="phone" placeholder="전화번호를 입력하세요" required><br>
        <input type="password" name="password" placeholder="비밀번호를 입력하세요" required><br>
        <small class="password-info">＊비밀번호는 영문, 숫자, 특수문자를 포함한 8~16자여야 합니다.</small><br>
        <button type="submit">회원가입</button>
    </form>
    <hr>
    <p>
        <a href="login.php"><button>로그인 하러가기</button></a>
        <a href="main.php"><button>메인으로 돌아가기</button></a>
    </p>
</div>
</body>
</html>
