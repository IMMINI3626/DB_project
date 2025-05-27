<?php
include 'db.php';
session_start();

// 조건 필터 처리 (기본값: 전체 출력)
$location = $_GET['location'] ?? '';
$min_price = $_GET['min_price'] ?? 0;
$max_price = $_GET['max_price'] ?? 10000000;
$size = $_GET['size'] ?? '';

// 기본 쿼리
$sql = "SELECT * FROM Room WHERE price BETWEEN ? AND ?";
$params = [$min_price, $max_price];
$types = "ii";

if (!empty($location)) {
    $sql .= " AND location LIKE ?";
    $params[] = "%$location%";
    $types .= "s";
}
if (!empty($size)) {
    $sql .= " AND size >= ?";
    $params[] = $size;
    $types .= "d";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="css/main.css">
    <meta charset="UTF-8">
    <title>자취방 목록</title>
</head>
<body>
<h1>자취방 목록</h1>
<form method="get" action="">
    지역: <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>">
    최소 가격: <input type="number" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>">
    최대 가격: <input type="number" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>">
    최소 평수: <input type="number" step="1" name="size" value="<?php echo htmlspecialchars($size); ?>">
    <button type="submit">검색</button>
</form>

<hr>
<?php if ($result->num_rows > 0): ?>
    <ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li>
            <strong><?php echo htmlspecialchars($row['room_name']); ?></strong><br>
            위치: <?php echo htmlspecialchars($row['location']); ?><br>
            가격: <?php echo htmlspecialchars($row['price']); ?> 원<br>
            평수: <?php echo htmlspecialchars($row['size']); ?> 평<br>
            <a href="room_detail.php?room_id=<?php echo $row['room_id']; ?>">상세 보기</a>
        </li><br>
    <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>검색 결과가 없습니다.</p>
<?php endif; ?>

<p>
    <a href="main.html">
    <button type="button">메인으로 돌아가기</button>
    </a>
</p>


<?php $stmt->close(); ?>
</body>
</html>
