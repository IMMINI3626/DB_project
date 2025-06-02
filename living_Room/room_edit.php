<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$room_id = $_GET['room_id'] ?? null;
if (!$room_id) {
    die("잘못된 접근입니다.");
}

// 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_name = $_POST['room_name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $size = $_POST['size'];

    $stmt = $conn->prepare("UPDATE Room SET room_name=?, location=?, price=?, size=? WHERE room_id=?");
    $stmt->bind_param("ssdsi", $room_name, $location, $price, $size, $room_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_rooms.php");
    exit;
}

// 기존 값 가져오기
$stmt = $conn->prepare("SELECT * FROM Room WHERE room_id=?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>자취방 수정</title>
</head>
<body>
<h1>자취방 수정</h1>
<form method="post">
    <label>방 이름: <input type="text" name="room_name" value="<?php echo htmlspecialchars($room['room_name']); ?>" required></label><br>
    <label>위치: <input type="text" name="location" value="<?php echo htmlspecialchars($room['location']); ?>" required></label><br>
    <label>가격(원): <input type="number" name="price" value="<?php echo $room['price']; ?>" required></label><br>
    <label>면적(㎡): <input type="number" step="0.1" name="size" value="<?php echo $room['size']; ?>" required></label><br>
    <button type="submit">수정 완료</button>
</form>
<p><a href="admin_rooms.php">← 목록으로</a></p>
</body>
</html>
