<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die('로그인이 필요합니다.');
}

$user_id = $_SESSION['user_id'];
$room_id = (int)($_GET['room_id'] ?? 0);

// 기존 리뷰 가져오기
$stmt = $conn->prepare("SELECT rating, content FROM Review WHERE user_id = ? AND room_id = ?");
$stmt->bind_param("ii", $user_id, $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('작성한 리뷰가 없습니다.');
}

$review = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>리뷰 수정</title>
    <link rel="stylesheet" href="css/review_edit.css">
</head>
<body>
    <h1>리뷰 수정</h1>
    <form method="post" action="review_update.php">
        <input type="hidden" name="room_id" value="<?= $room_id ?>">
        평점 (1~5):
        <input type="number" name="rating" min="1" max="5" value="<?= $review['rating'] ?>" required><br>
        내용:<br>
        <textarea name="content" rows="5" required><?= htmlspecialchars($review['content']) ?></textarea><br>
        <button type="submit">리뷰 수정</button>
    </form>

    <form method="post" action="review_delete.php" onsubmit="return confirm('정말 삭제하시겠습니까?');">
        <input type="hidden" name="room_id" value="<?= $room_id ?>">
        <button type="submit">리뷰 삭제</button>
    </form>

    <a href="room_detail.php?room_id=<?= $room_id ?>">← 방 상세 페이지로 돌아가기</a>
    <p><a href="rooms_list.php?room_id=<?= $room_id ?>">← 자취방 목록으로 돌아가기</a></p>
</body>
</html>
