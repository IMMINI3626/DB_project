<?php
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim($_POST['name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $user_type = 'user'; // 기본 사용자 유형

    // 필수 입력값 체크
    if (empty($name) || empty($email) || empty($password)) {
        $error = '모든 필드를 입력해주세요.';
    } else {
        // 이메일 중복 체크
        $checkStmt = $conn->prepare("SELECT user_id FROM User WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result && $result->num_rows > 0) {
            $error = '이미 등록된 이메일입니다.';
        } else {
            // 비밀번호 해싱
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // 회원 정보 DB에 저장
            $insertStmt = $conn->prepare(
                "INSERT INTO User (name, email, password, user_type, created_at)
                 VALUES (?, ?, ?, ?, NOW())"
            );
            $insertStmt->bind_param("ssss", $name, $email, $hashedPassword, $user_type);
            
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
    <meta charset="UTF-8">
    <title>회원가입</title>
</head>
<body>
    <h1>회원가입</h1>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="">
        이름: <input type="text" name="name" required><br>
        이메일: <input type="email" name="email" required><br>
        비밀번호: <input type="password" name="password" required><br>
        <br><button type="submit">회원가입</button>     
        <a href="main.html">
    <button type="button">메인으로 돌아가기</button>
</a>

    </form>
    
</body>
</html>
