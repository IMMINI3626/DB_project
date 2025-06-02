<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 자취방 추가 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_name = $_POST['room_name'] ?? '';
    $location = $_POST['location'] ?? '';
    $price = $_POST['price'] ?? 0;
    $size = $_POST['size'] ?? 0;

    $user_id = $_SESSION['user_id'];  // 여기! 관리자 ID 자동 대입

    if (!empty($room_name) && !empty($location) && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO Room (user_id, room_name, location, price, size, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issdd", $user_id, $room_name, $location, $price, $size);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_rooms.php");  // 다시 목록으로 이동
        exit;
    } else {
        $error = "모든 필드를 정확히 입력하세요.";
    }
}


$result = $conn->query("SELECT * FROM Room ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>자취방 관리</title>
</head>
<body>
<h1>자취방 목록</h1>
<h3>자취방 추가</h3>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post" action="">
    <label>방 이름: <input type="text" name="room_name" required></label><br>
    <label>위치: <input type="text" name="location" required></label><br>
    <label>가격(원): <input type="number" name="price" required></label><br>
    <label>면적(㎡): <input type="number" step="0.1" name="size" required></label><br>
    <button type="submit">자취방 추가</button>
</form>
<hr>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <strong><?php echo htmlspecialchars($row['room_name']); ?></strong>
        (<?php echo htmlspecialchars($row['location']); ?> / <?php echo number_format($row['price']); ?>원)
        <a href="room_detail.php?room_id=<?php echo $row['room_id']; ?>">보기</a>
        <a href="room_edit.php?room_id=<?php echo $row['room_id']; ?>">수정</a>
        <a href="room_delete.php?room_id=<?php echo $row['room_id']; ?>" onclick="return confirm('삭제하시겠습니까?');">삭제</a>
    </li>
<?php endwhile; ?>
</ul>

<p><a href="admin_dashboard.php">← 돌아가기</a></p>
</body>
</html>
