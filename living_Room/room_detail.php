<?php
include 'db.php';
session_start();

$room_id = $_GET['room_id'] ?? null;
if (!$room_id) die('잘못된 접근입니다.');

// 방 정보 조회
$sql = "SELECT * FROM Room WHERE room_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die('해당 방을 찾을 수 없습니다.');
$Room = $result->fetch_assoc();
$stmt->close();

// 옵션 조회
$sql = "SELECT f.feature_name FROM Feature f JOIN RoomFeature rf ON f.feature_id = rf.feature_id WHERE rf.room_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$options = $stmt->get_result();

// 후기 조회
$sql = "SELECT u.name, r.rating, r.content, r.created_at FROM Review r JOIN User u ON r.user_id = u.user_id WHERE r.room_id = ? ORDER BY r.created_at DESC";
$review_stmt = $conn->prepare($sql);
$review_stmt->bind_param("i", $room_id);
$review_stmt->execute();
$reviews = $review_stmt->get_result();

// 좋아요 수 조회
$sql = "SELECT COUNT(*) as like_count FROM Likes WHERE room_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$like_count = $stmt->get_result()->fetch_assoc()['like_count'];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="css/room_detail.css">
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($Room['room_name']) ?> - 상세 정보</title>
</head>
<body>
<h1><?= htmlspecialchars($Room['room_name']) ?> 상세 정보</h1>
<p>위치: <?= htmlspecialchars($Room['location']) ?></p>
<p>가격: <?= htmlspecialchars($Room['price']) ?> 원</p>
<p>평수: <?= htmlspecialchars($Room['size']) ?> 평</p>
<p>등록일: <?= htmlspecialchars($Room['created_at']) ?></p>
<p>설명: <?= nl2br(htmlspecialchars($Room['description'])) ?></p>

<h3>제공 옵션</h3>
<ul>
<?php while ($opt = $options->fetch_assoc()): ?>
    <li><?= htmlspecialchars($opt['feature_name']) ?></li>
<?php endwhile; ?>
</ul>

<h3>좋아요 ♥ (<?= $like_count ?>)</h3>

<h3>후기</h3>
<?php if ($reviews->num_rows > 0): ?>
    <?php while ($r = $reviews->fetch_assoc()): ?>
        <div>
            <strong><?= htmlspecialchars($r['name']) ?></strong> (<?= $r['rating'] ?>/5)<br>
            <small><?= $r['created_at'] ?></small><br>
            <p><?= nl2br(htmlspecialchars($r['content'])) ?></p>
            <hr>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>아직 후기가 없습니다.</p>
<?php endif; ?>

<a href="rooms_list.php">← 목록으로 돌아가기</a>
</body>
</html>
