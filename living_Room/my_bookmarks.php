<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.room_id, r.room_name, r.location, r.price, r.size, r.created_at
        FROM Bookmark b
        JOIN Room r ON b.room_id = r.room_id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="css/main.css">
    <meta charset="UTF-8">
    <title>내 북마크 목록</title>
</head>
<body>
<h1>내가 북마크한 자취방</h1>
<?php if ($result->num_rows > 0): ?>
    <ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li>
            <strong><?php echo htmlspecialchars($row['room_name']); ?></strong><br>
            위치: <?php echo htmlspecialchars($row['location']); ?><br>
            가격: <?php echo htmlspecialchars($row['price']); ?> 원<br>
            평수: <?php echo htmlspecialchars($row['size']); ?> 평<br>
            등록일: <?php echo htmlspecialchars($row['created_at']); ?><br>
            <a href="room_detail.php?room_id=<?php echo $row['room_id']; ?>">상세 보기</a>
        </li><br>
    <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>북마크한 자취방이 없습니다.</p>
<?php endif; ?>

<a href="rooms_list.php">← 자취방 목록으로</a>
</body>
</html>