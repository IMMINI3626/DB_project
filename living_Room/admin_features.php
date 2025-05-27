<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}
// 추가 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['new_feature'])) {
        $stmt = $conn->prepare("INSERT INTO Feature (feature_name) VALUES (?)");
        $stmt->bind_param("s", $_POST['new_feature']);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_id'])) {
        $stmt = $conn->prepare("DELETE FROM Feature WHERE feature_id = ?");
        $stmt->bind_param("i", $_POST['delete_id']);
        $stmt->execute();
        $stmt->close();
    }
}
$features = $conn->query("SELECT * FROM Feature ORDER BY feature_name ASC");
?>
<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>옵션 목록 관리</title></head>
<body>
<h1>옵션 관리</h1>
<form method="post">
    새 옵션 이름: <input type="text" name="new_feature" required>
    <button type="submit">추가</button>
</form>
<ul>
<?php while ($row = $features->fetch_assoc()): ?>
    <li>
        <?php echo htmlspecialchars($row['feature_name']); ?>
        <form method="post" style="display:inline;">
            <input type="hidden" name="delete_id" value="<?php echo $row['feature_id']; ?>">
            <button type="submit" onclick="return confirm('삭제하시겠습니까?');">삭제</button>
        </form>
    </li>
<?php endwhile; ?>
</ul>
<a href="admin_dashboard.php">← 돌아가기</a>
</body></html>
