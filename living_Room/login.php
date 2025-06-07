<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = '이메일과 비밀번호를 모두 입력해주세요.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM User WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $User = $result->fetch_assoc();

            // 관리자는 로그인 차단
            if ($User['user_type'] === 'admin') {
                $error = '관리자는 이 페이지에서 로그인할 수 없습니다.';
            }
            // 일반 사용자만 로그인 허용
            else if ($User['password'] === $password || password_verify($password, $User['password'])) {
                $_SESSION['user_id'] = $User['user_id'];
                $_SESSION['user_name'] = $User['name'];
                $_SESSION['user_type'] = $User['user_type'];

                header('Location: main.php');
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
<div class="info-text">
    <p>관리자 로그인은 <a href="admin_passcheck.php">여기</a>를 클릭하세요.</p>
</div>
<div class="login-container">
    <h1>로그인</h1>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="">
        <input type="email" name="email" placeholder="이메일을 입력하세요" required><br>
        <input type="password" name="password" placeholder="비밀번호를 입력하세요" required><br>
        <button type="submit">로그인</button>
    </form>
    <hr>
    <p>
        <a href="main.php"><button>메인으로 돌아가기</button></a>
    </p>
    <div class="row-text">
    <p>아직 회원이 아니신가요? <a href="join_user.php">회원가입</a></p>
    <p>비밀번호를 잊으셨나요? <a href="reset_password.php">비밀번호 재설정</a></p>
    </div>
</div>
</body>
</html>
