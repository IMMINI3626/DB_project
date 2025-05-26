<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}
if (isset($_POST['delete_user'])) {
    $uid = (int)$_POST['user_id'];
    $stmt = $conn->prepare("DELETE FROM User WHERE user_id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->close();
}
$users = $conn->query("SELECT * FROM User ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>사용자 관리</title></head>
<body>
<h1>사용자 관리</h1>
<ul>
<?php while ($row = $users->fetch_assoc()): ?>
    <li>
        <?php echo htmlspecialchars($row['name']); ?> (<?php echo $row['email']; ?>)
        <?php if ($row['user_type'] !== 'admin'): ?>
        <form method="post" style="display:inline;">
            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
            <button type="submit" name="delete_user" onclick="return confirm('사용자를 삭제하시겠습니까?');">삭제</button>
        </form>
        <?php endif; ?>
    </li>
<?php endwhile; ?>
</ul>
<a href="admin_dashboard.php">← 돌아가기</a>
</body></html>
