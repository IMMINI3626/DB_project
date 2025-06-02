<?php
include 'db.php';
session_start();

$location = $_GET['location'] ?? '';
$min_price = $_GET['min_price'] ?? 0;
$max_price = $_GET['max_price'] ?? 10000000;
$size = $_GET['size'] ?? '';
$sort = $_GET['sort'] ?? '';

$params = [$min_price, $max_price];
$types = "ii";

// 정렬 기준에 따라 SQL 시작
if ($sort === 'likes') {
    $sql = "SELECT Room.*, 
                   (SELECT COUNT(*) FROM Likes WHERE Likes.room_id = Room.room_id) AS like_count 
            FROM Room 
            WHERE price BETWEEN ? AND ?";
} else {
    $sql = "SELECT * FROM Room WHERE price BETWEEN ? AND ?";
}

// 필터 조건
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

// 정렬 기준
if ($sort === 'likes') {
    $sql .= " ORDER BY like_count DESC";
} elseif ($sort === 'recent') {
    $sql .= " ORDER BY created_at DESC";
}

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
    <div class="form-group">
        <label for="sort">정렬:</label>
        <select id="sort" name="sort">
            <option value="">-- 선택 --</option>
            <option value="likes" <?= $sort === 'likes' ? 'selected' : '' ?>>좋아요 많은 순</option>
            <option value="recent" <?= $sort === 'recent' ? 'selected' : '' ?>>최신순</option>
        </select>
    </div>
    <button type="submit">검색</button>
    <a href="main.php"><button type="button">메인으로 돌아가기</button></a>
</form>
<hr>

<?php if ($result->num_rows > 0): ?>
    <ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li class="room-item">
            <div class="room-info">
                <strong><?php echo htmlspecialchars($row['room_name']); ?></strong>
                위치: <?php echo htmlspecialchars($row['location']); ?><br>
                가격: <?php echo number_format($row['price']); ?> 원<br>
                평수: <?php echo htmlspecialchars($row['size']); ?> 평<br><br>
                <a href="room_detail.php?room_id=<?php echo $row['room_id']; ?>">상세 보기</a>
            </div>

            <div class="room-icons">
                <?php
                $check_like = $conn->prepare("SELECT 1 FROM Likes WHERE user_id = ? AND room_id = ?");
                $check_like->bind_param("ii", $_SESSION['user_id'], $row['room_id']);
                $check_like->execute();
                $is_liked = $check_like->get_result()->num_rows > 0;
                $check_like->close();
                ?>
                <button class="icon-button like" data-room-id="<?= $row['room_id'] ?>" data-liked="<?= $is_liked ? '1' : '0' ?>">
                    <?= $is_liked ? '❤︎' : '♡' ?>
                </button>

                <?php
                $check_bookmark = $conn->prepare("SELECT 1 FROM Bookmark WHERE user_id = ? AND room_id = ?");
                $check_bookmark->bind_param("ii", $_SESSION['user_id'], $row['room_id']);
                $check_bookmark->execute();
                $is_bookmarked = $check_bookmark->get_result()->num_rows > 0;
                $check_bookmark->close();
                ?>
                <button class="icon-button bookmark" data-room-id="<?= $row['room_id'] ?>" data-bookmarked="<?= $is_bookmarked ? '1' : '0' ?>">
                    <?= $is_bookmarked ? '🔖' : '📑' ?>
                </button>
            </div>

            <div class="room-review">
                <?php
                $check_review = $conn->prepare("SELECT 1 FROM Review WHERE user_id = ? AND room_id = ?");
                $check_review->bind_param("ii", $_SESSION['user_id'], $row['room_id']);
                $check_review->execute();
                $has_review = $check_review->get_result()->num_rows > 0;
                $check_review->close();
                ?>
                <a href="<?= $has_review ? 'review_edit.php' : 'review_write.php' ?>?room_id=<?= $row['room_id'] ?>" class="btn-review">
                    <?= $has_review ? '리뷰 수정하기' : '리뷰 작성하기' ?>
                </a>
            </div>
        </li>
    <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>검색 결과가 없습니다.</p>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // 좋아요
  document.querySelectorAll('.icon-button.like').forEach(button => {
    button.addEventListener('click', function () {
      const roomId = this.dataset.roomId;
      const liked = this.dataset.liked === '1';
      const action = liked ? 'unlike' : 'like';
      const btn = this;

      fetch('like_toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `room_id=${roomId}&action=${action}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          btn.textContent = data.liked ? '❤︎' : '♡';
          btn.dataset.liked = data.liked ? '1' : '0';
        } else {
          alert('로그인이 필요합니다.');
        }
      })
      .catch(() => alert('오류 발생'));
    });
  });

  // 북마크
  document.querySelectorAll('.icon-button.bookmark').forEach(button => {
    button.addEventListener('click', function () {
      const roomId = this.dataset.roomId;
      const bookmarked = this.dataset.bookmarked === '1';
      const action = bookmarked ? 'unbookmark' : 'bookmark';
      const btn = this;

      fetch('bookmark_toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `room_id=${roomId}&action=${action}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          btn.textContent = data.bookmarked ? '🔖' : '📑';
          btn.dataset.bookmarked = data.bookmarked ? '1' : '0';
        } else {
          alert('로그인이 필요합니다.');
        }
      })
      .catch(() => alert('오류 발생'));
    });
  });
});
</script>

</body>
</html>
