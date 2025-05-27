<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 옵션 불러오기
$feature_result = $conn->query("SELECT * FROM Feature ORDER BY feature_name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_name = trim($_POST['room_name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $size = (float)($_POST['size'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $features = $_POST['features'] ?? [];

    if ($room_name && $location && $price > 0 && $size > 0) {
        $stmt = $conn->prepare("INSERT INTO Room (user_id, room_name, location, price, size, description, created_at)
                                VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issdds", $_SESSION['user_id'], $room_name, $location, $price, $size, $description);
        $stmt->execute();
        $room_id = $stmt->insert_id;
        $stmt->close();

        // 옵션 저장
        if (!empty($features)) {
            $rf_stmt = $conn->prepare("INSERT INTO RoomFeature (room_id, feature_id) VALUES (?, ?)");
            foreach ($features as $fid) {
                $rf_stmt->bind_param("ii", $room_id, $fid);
                $rf_stmt->execute();
            }
            $rf_stmt->close();
        }

        header('Location: rooms_list.php');
        exit;
    } else {
        $error = "모든 필드를 올바르게 입력해주세요.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="css/main.css">
    <meta charset="UTF-8">
    <title>자취방 등록</title>
</head>
<body>
<h1>자취방 등록</h1>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post" action="">
    방 이름: <input type="text" name="room_name" required><br>
    위치: <input type="text" name="location" required><br>
    월세 (원): <input type="number" name="price" step="0.01" required><br>
    평수: <input type="number" name="size" step="0.1" required><br>
    설명:<br>
    <textarea name="description" rows="5" cols="50"></textarea><br>

    <h3>옵션 선택</h3>
    <?php while ($f = $feature_result->fetch_assoc()): ?>
        <label>
            <input type="checkbox" name="features[]" value="<?php echo $f['feature_id']; ?>">
            <?php echo htmlspecialchars($f['feature_name']); ?>
        </label><br>
    <?php endwhile; ?>

    <button type="submit">등록하기</button>
</form>
</body>
</html>
