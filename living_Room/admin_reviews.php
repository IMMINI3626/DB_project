<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}
if (isset($_POST['delete_review'])) {
    $uid = (int)$_POST['user_id'];
    $rid = (int)$_POST['room_id'];
    $stmt = $conn->prepare("DELETE FROM Review WHERE user_id = ? AND room_id = ?");
    $stmt->bind_param("ii", $uid, $rid);
    $stmt->execute();
    $stmt->close();
}
$reviews = $conn->query("SELECT r.user_id, r.room_id, r.rating, r.content, u.name, rm.room_name FROM Review r
JOIN User u ON r.user_id = u.user_id
JOIN Room rm ON r.room_id = rm.room_id ORDER BY r.created_at DESC");
?>
<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>후기 관리</title></head>
<body>
<h1>후기 관리</h1>
<ul>
<?php while ($row = $reviews->fetch_assoc()): ?>
    <li>
        <strong><?php echo htmlspecialchars($row['name']); ?></strong> → 
        <em><?php echo htmlspecialchars($row['room_name']); ?></em> (<?php echo $row['rating']; ?>/5)
        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        <form method="post" style="display:inline;">
            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
            <input type="hidden" name="room_id" value="<?php echo $row['room_id']; ?>">
            <button type="submit" name="delete_review" onclick="return confirm('후기를 삭제하시겠습니까?');">삭제</button>
        </form>
    </li><hr>
<?php endwhile; ?>
</ul>
<a href="admin_dashboard.php">← 돌아가기</a>
</body></html>
