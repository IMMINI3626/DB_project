<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$result = $conn->query("SELECT * FROM Room ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>자취방 관리</title></head>
<body>
<h1>자취방 목록</h1>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <strong><?php echo htmlspecialchars($row['room_name']); ?></strong>
        (<?php echo htmlspecialchars($row['location']); ?>, <?php echo $row['price']; ?>원)
        <a href="room_detail.php?room_id=<?php echo $row['room_id']; ?>">보기</a>
        <a href="room_edit.php?room_id=<?php echo $row['room_id']; ?>">수정</a>
        <a href="room_delete.php?room_id=<?php echo $row['room_id']; ?>" onclick="return confirm('삭제하시겠습니까?');">삭제</a>
    </li>
<?php endwhile; ?>
</ul>
<a href="admin_dashboard.php">← 돌아가기</a>
</body></html>
