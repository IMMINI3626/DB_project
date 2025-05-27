<?php
include 'db.php';
session_start();

$room_id = $_GET['room_id'] ?? null;
if (!$room_id) {
    die('잘못된 접근입니다.');
}

// 방 정보 조회
$sql = "SELECT * FROM Room WHERE room_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('해당 방을 찾을 수 없습니다.');
}
$Room = $result->fetch_assoc();
$stmt->close();

// 옵션 조회
$sql = "SELECT f.feature_name FROM Feature f
        JOIN RoomFeature rf ON f.feature_id = rf.feature_id
        WHERE rf.room_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$options = $stmt->get_result();

// 후기 조회
$sql = "SELECT u.name, r.rating, r.content, r.created_at
        FROM Review r JOIN User u ON r.user_id = u.user_id
        WHERE r.room_id = ? ORDER BY r.created_at DESC";
$review_stmt = $conn->prepare($sql);
$review_stmt->bind_param("i", $room_id);
$review_stmt->execute();
$reviews = $review_stmt->get_result();

// 좋아요 수 조회
$sql = "SELECT COUNT(*) as like_count FROM LikeRoom WHERE room_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$like_count = $stmt->get_result()->fetch_assoc()['like_count'];
$stmt->close();

// 북마크 여부 확인
$bookmarked = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT 1 FROM Bookmark WHERE user_id = ? AND room_id = ?");
    $stmt->bind_param("ii", $user_id, $room_id);
    $stmt->execute();
    $bookmarked = $stmt->get_result()->num_rows > 0;
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="css/main.css">
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($Room['room_name']); ?> - 상세 정보</title>
</head>
<body>
<h1><?php echo htmlspecialchars($Room['room_name']); ?> 상세 정보</h1>
<p>위치: <?php echo htmlspecialchars($Room['location']); ?></p>
<p>가격: <?php echo htmlspecialchars($Room['price']); ?> 원</p>
<p>평수: <?php echo htmlspecialchars($Room['size']); ?> 평</p>
<p>등록일: <?php echo htmlspecialchars($Room['created_at']); ?></p>
<p>설명: <?php echo nl2br(htmlspecialchars($Room['description'])); ?></p>

<h3>제공 옵션</h3>
<ul>
    <?php while ($opt = $options->fetch_assoc()): ?>
        <li><?php echo htmlspecialchars($opt['feature_name']); ?></li>
    <?php endwhile; ?>
</ul>

<h3>좋아요 ♥ (<?php echo $like_count; ?>)</h3>
<?php if (isset($_SESSION['user_id'])): ?>
<form method="post" action="like_toggle.php">
    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
    <?php
    $stmt = $conn->prepare("SELECT 1 FROM LikeRoom WHERE user_id = ? AND room_id = ?");
    $stmt->bind_param("ii", $_SESSION['user_id'], $room_id);
    $stmt->execute();
    $liked = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    ?>
    <button type="submit" name="action" value="<?php echo $liked ? 'unlike' : 'like'; ?>">
        <?php echo $liked ? '좋아요 취소' : '좋아요'; ?>
    </button>
</form>
<?php else: ?>
<p><a href="login.php">로그인</a> 후 좋아요를 누를 수 있습니다.</p>
<?php endif; ?>

<h3>북마크</h3>
<?php if (isset($_SESSION['user_id'])): ?>
<form method="post" action="bookmark_toggle.php">
    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
    <button type="submit" name="action" value="<?php echo $bookmarked ? 'unbookmark' : 'Bookmark'; ?>">
        <?php echo $bookmarked ? '북마크 취소' : '북마크'; ?>
    </button>
</form>
<?php else: ?>
<p><a href="login.php">로그인</a> 후 북마크할 수 있습니다.</p>
<?php endif; ?>

<h3>후기</h3>
<?php if (isset($_SESSION['user_id'])): ?>
<form method="post" action="review_add.php">
    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
    평점 (1~5): <input type="number" name="rating" min="1" max="5" required><br>
    내용:<br>
    <textarea name="content" rows="4" cols="50" required></textarea><br>
    <button type="submit">후기 작성</button>
</form>
<?php else: ?>
<p><a href="login.php">로그인</a> 후 후기를 작성할 수 있습니다.</p>
<?php endif; ?>

<?php if ($reviews->num_rows > 0): ?>
    <?php while ($r = $reviews->fetch_assoc()): ?>
        <div>
            <strong><?php echo htmlspecialchars($r['name']); ?></strong> (<?php echo $r['rating']; ?>/5)<br>
            <small><?php echo $r['created_at']; ?></small><br>
            <p><?php echo nl2br(htmlspecialchars($r['content'])); ?></p>
            <hr>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>아직 후기가 없습니다.</p>
<?php endif; ?>

<a href="rooms_list.php">← 목록으로 돌아가기</a>
</body>
</html>