<?php
session_start();

// admin_pass_verified 세션 체크
if (!isset($_SESSION['admin_pass_verified']) || $_SESSION['admin_pass_verified'] !== true) {
    header('Location: admin_passcheck.php');
    exit;
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = '이메일과 비밀번호를 모두 입력해주세요.';
    } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/', $password)) {
        $error = '비밀번호는 영문, 숫자, 특수문자를 포함한 8~16자여야 합니다.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM User WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $User = $result->fetch_assoc();

            if ($User['user_type'] !== 'admin') {
                $error = '관리자 권한이 없는 계정입니다.';
            } elseif (password_verify($password, $User['password'])) {
                $_SESSION['user_id'] = $User['user_id'];
                $_SESSION['user_name'] = $User['name'];
                $_SESSION['user_type'] = $User['user_type'];

                header('Location: admin_dashboard.php');
                exit;
            } else {
                $error = '비밀번호가 일치하지 않습니다.';
            }
        } else {
            $error = '해당 이메일로 등록된 사용자가 없습니다.';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="css/login.css">
    <meta charset="UTF-8">
    <title>로그인</title>
</head>
<body>
<div class="login-container">
    <h1>관리자 로그인</h1>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="">
        <input type="email" name="email" placeholder="이메일을 입력하세요" required><br>
        <input type="password" name="password" placeholder="비밀번호를 입력하세요" required><br>
        <button type="submit">로그인</button>
    </form>
    <hr>
    <p>
        <a href="join_user.php"><button>회원가입 하러가기</button></a>
        <a href="main.php"><button>메인으로 돌아가기</button></a>
    </p>
</div>
</body>

</html>
