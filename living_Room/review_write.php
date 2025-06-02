<?php
include 'db.php';
session_start();

$room_id = $_GET['room_id'] ?? null;
if (!$room_id) die('잘못된 접근입니다.');

$stmt = $conn->prepare("SELECT room_name FROM Room WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>리뷰 작성 - <?= htmlspecialchars($room['room_name']) ?></title>
    <link rel="stylesheet" href="css/review_write.css">
</head>
<body>
<h2><?= htmlspecialchars($room['room_name']) ?>에 대한 리뷰 작성</h2>

<?php if (isset($_SESSION['user_id'])): ?>
<form id="review-form" method="post">
    <input type="hidden" name="room_id" value="<?= $room_id ?>">
    
    <label for="rating">평점 (1~5):</label>
    <input type="number" name="rating" min="1" max="5" required><br>

    <label for="content">내용:</label><br>
    <textarea name="content" rows="5" cols="60" required></textarea><br>

    <button type="submit">리뷰 등록</button>
</form>
<?php else: ?>
<p><a href="login.php">로그인</a> 후 리뷰를 작성할 수 있습니다.</p>
<?php endif; ?>

<p><a href="room_detail.php?room_id=<?= $room_id ?>">← 방 상세로 돌아가기</a></p>

<script>
document.getElementById('review-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const res = await fetch('review_add.php', {
        method: 'POST',
        body: formData
    });

    const result = await res.json();

    if (result.status === 'success') {
        alert('리뷰가 성공적으로 등록되었습니다!');
        location.href = `room_detail.php?room_id=${formData.get('room_id')}`;
    } else {
        alert('리뷰 등록에 실패했습니다.');
    }
});
</script>
</body>
</html>
