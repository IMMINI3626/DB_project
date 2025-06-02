<?php
include 'db.php';
session_start();

// 관리자 인증 확인
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 사용자 삭제 요청 처리
if (isset($_POST['delete_user'])) {
    $uid = (int)$_POST['user_id'];
    $stmt = $conn->prepare("DELETE FROM User WHERE user_id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->close();
}

// 숫자 기준 이름 정렬 (예: User1, User2, ..., User10)
$users = $conn->query("SELECT * FROM User WHERE user_type != 'admin' ORDER BY CAST(SUBSTRING(name, 5) AS UNSIGNED) ASC");
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>사용자 관리</title>
</head>
<body>
<h1>사용자 관리</h1>
<ul>
<?php while ($row = $users->fetch_assoc()): ?>
    <li>
        <?php echo htmlspecialchars($row['name']); ?> (<?php echo $row['email']; ?>)
        <form method="post" style="display:inline;">
            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
            <button type="submit" name="delete_user" onclick="return confirm('사용자를 삭제하시겠습니까?');">삭제</button>
        </form>
    </li>
<?php endwhile; ?>
</ul>

<a href="admin_dashboard.php">← 돌아가기</a>
</body>
</html>
