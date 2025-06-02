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
    <meta charset="UTF-8">
    <title>내 북마크 목록</title>
    <link rel="stylesheet" href="css/my_option.css">
</head>
<body>
<div class="container">
    <h1>내가 북마크한 자취방</h1>

    <div class="link-bar">
        <a class="back-link" href="rooms_list.php">← 자취방 목록으로</a>
        <a class="back-link" href="mypage.php">← 마이페이지로</a>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="room-box">
                <p><strong><?php echo htmlspecialchars($row['room_name']); ?></strong></p>
                <p>위치: <?php echo htmlspecialchars($row['location']); ?></p>
                <p>가격: <?php echo number_format($row['price']); ?> 원</p>
                <p>평수: <?php echo htmlspecialchars($row['size']); ?> 평</p>
                <p>등록일: <?php echo htmlspecialchars($row['created_at']); ?></p>
                <p><a href="room_detail.php?room_id=<?php echo $row['room_id']; ?>">상세 보기</a></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>북마크한 자취방이 없습니다.</p>
    <?php endif; ?>
</div>
</body>
</html>
