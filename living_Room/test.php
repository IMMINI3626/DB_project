<?php
include 'db.php';
session_start();

// CSRF 토큰 생성
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 정렬 기준 처리
$order = $_GET['order'] ?? 'popular';

// 조건 필터 처리 (기본값: 전체 출력)
$location = $_GET['location'] ?? '';
$min_price = $_GET['min_price'] ?? 0;
$max_price = $_GET['max_price'] ?? 10000000;
$size = $_GET['size'] ?? '';

// 좋아요 수 포함 쿼리
$sql = "SELECT r.*, COUNT(l.user_id) AS like_count 
        FROM Room r
        LEFT JOIN Likes l ON r.room_id = l.room_id
        WHERE r.price BETWEEN ? AND ?";
$params = [$min_price, $max_price];
$types = "ii";

if (!empty($location)) {
    $sql .= " AND r.location LIKE ?";
    $params[] = "%$location%";
    $types .= "s";
}
if (!empty($size)) {
    $sql .= " AND r.size >= ?";
    $params[] = $size;
    $types .= "d";
}

// 정렬 기준 추가
$sql .= " GROUP BY r.room_id 
          ORDER BY " . ($order === 'recent' 
              ? "r.created_at DESC" 
              : "like_count DESC, r.created_at DESC");

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="css/room_list.css">
    <meta charset="UTF-8">
    <title>자취방 목록</title>
</head>
<body>
<h1>자취방 목록</h1>

<form method="get" action="">
    <div class="form-group">
        <label for="location">지역(구, 동 단위):</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>">
    </div>

    <div class="form-group">
        <label for="min_price">최소 가격:</label>
        <input type="number" id="min_price" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>">

        <label for="max_price">최대 가격:</label>
        <input type="number" id="max_price" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>">
    </div>

    <div class="form-group">
        <label for="size">최소 평수:</label>
        <input type="number" id="size" name="size" step="1" value="<?php echo htmlspecialchars($size); ?>">
    </div>

    <div class="sort-section">
        <label for="order">정렬:</label>
        <select name="order" id="order">
            <option value="popular" <?= $order === 'popular' ? 'selected' : '' ?>>인기순</option>
            <option value="recent" <?= $order === 'recent' ? 'selected' : '' ?>>최신순</option>
        </select>
    </div>

    <button type="submit">검색</button>
</form>

<hr>

<?php if ($result->num_rows > 0): ?>
    <ul style="list-style: none; padding: 0;">
    <?php while ($row = $result->fetch_assoc()): ?>
        <li class="room-item">
            <strong><?php echo htmlspecialchars($row['room_name']); ?></strong> 
            <span style="color:#00796b;"❤️ <?= $row['like_count'] ?></span>
            <br>
            위치: <?php echo htmlspecialchars($row['location']); ?><br>
            가격: <?php echo number_format($row['price']); ?> 원<br>
            평수: <?php echo htmlspecialchars($row['size']); ?> 평<br>

            <div class="room-actions">
                <a href="room_detail.php?room_id=<?php echo $row['room_id']; ?>" class="btn-detail">상세 보기</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- 좋아요 버튼 -->
                    <?php
                    $check_like = $conn->prepare("SELECT 1 FROM Likes WHERE user_id = ? AND room_id = ?");
                    $check_like->bind_param("ii", $_SESSION['user_id'], $row['room_id']);
                    $check_like->execute();
                    $is_liked = $check_like->get_result()->num_rows > 0;
                    $check_like->close();
                    ?>
                    <form method="post" action="like_toggle.php">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="room_id" value="<?= $row['room_id'] ?>">
                        <button type="submit" name="action" value="<?= $is_liked ? 'unlike' : 'like' ?>" class="btn-like">
                            <?= $is_liked ? '❤️' : '🤍' ?>
                        </button>
                    </form>

                    <!-- 북마크 버튼 -->
                    <?php
                    $check_bookmark = $conn->prepare("SELECT 1 FROM Bookmark WHERE user_id = ? AND room_id = ?");
                    $check_bookmark->bind_param("ii", $_SESSION['user_id'], $row['room_id']);
                    $check_bookmark->execute();
                    $is_bookmarked = $check_bookmark->get_result()->num_rows > 0;
                    $check_bookmark->close();
                    ?>
                    <form method="post" action="bookmark_toggle.php">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="room_id" value="<?= $row['room_id'] ?>">
                        <button type="submit" name="action" value="<?= $is_bookmarked ? 'unbookmark' : 'Bookmark' ?>" class="btn-bookmark">
                            <?= $is_bookmarked ? '북마크 해제' : '북마크' ?>
                        </button>
                    </form>

                    <!-- 리뷰 남기기 -->
                    <a href="room_detail.php?room_id=<?= $row['room_id'] ?>#review-form" class="btn-review">리뷰 남기기</a>
                    
                <?php else: ?>
                    <div class="login-notice">
                        <a href="login.php">로그인</a> 후 좋아요/북마크/리뷰 작성이 가능합니다
                    </div>
                <?php endif; ?>
            </div>
        </li>
    <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>검색 결과가 없습니다.</p>
<?php endif; ?>

<p>
    <a href="main.php">
        <button type="button">메인으로 돌아가기</button>
    </a>
</p>

<?php $stmt->close(); ?>
</body>
</html>
