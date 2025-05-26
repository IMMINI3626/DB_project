<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.room_name, rv.rating, rv.content, rv.created_at, rv.room_id
        FROM Review rv
        JOIN Room r ON rv.room_id = r.room_id
        WHERE rv.user_id = ?
        ORDER BY rv.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>내 후기 목록</title>
</head>
<body>
<h1>내가 작성한 후기</h1>
<?php if ($result->num_rows > 0): ?>
    <ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li>
            <strong><?php echo htmlspecialchars($row['room_name']); ?></strong><br>
            평점: <?php echo $row['rating']; ?>/5<br>
            작성일: <?php echo $row['created_at']; ?><br>
            내용:<br>
            <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
            <a href="room_detail.php?room_id=<?php echo $row['room_id']; ?>">방 보러가기</a>
        </li><br>
    <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>작성한 후기가 없습니다.</p>
<?php endif; ?>

<a href="rooms_list.php">← 자취방 목록으로</a>
</body>
</html>