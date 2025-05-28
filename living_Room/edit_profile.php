<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// 사용자 정보 불러오기
$stmt = $conn->prepare("SELECT name, phone, email FROM User WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($name) || empty($phone) || empty($email)) {
        $error = '모든 필드를 입력해주세요.';
    } else {
        // 이메일 중복 확인 (본인 이메일 제외)
        $checkStmt = $conn->prepare("SELECT user_id FROM User WHERE email = ? AND user_id != ?");
        $checkStmt->bind_param("si", $email, $user_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult && $checkResult->num_rows > 0) {
            $error = '이미 사용 중인 이메일입니다.';
        } else {
            $updateStmt = $conn->prepare("UPDATE User SET name = ?, phone = ?, email = ? WHERE user_id = ?");
            $updateStmt->bind_param("sssi", $name, $phone, $email, $user_id);
            if ($updateStmt->execute()) {
                $success = '정보가 성공적으로 수정되었습니다.';
                $_SESSION['user_name'] = $name; // 세션 이름 업데이트
            } else {
                $error = '수정 실패: ' . $updateStmt->error;
            }
            $updateStmt->close();
        }
        $checkStmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>내 정보 수정</title>
    <link rel="stylesheet" href="css/edit_profile.css">
</head>
<body>
    <h1>내 정보 수정하기</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="name" placeholder="이름" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required><br>
        <input type="text" name="phone" placeholder="전화번호" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required><br>
        <input type="email" name="email" placeholder="이메일" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required><br>
        <button type="submit">수정하기</button>
    </form>

    <p><a href="mypage.php"><button>마이페이지로 돌아가기</button></a></p>
</body>
</html>
