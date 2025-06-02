<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <link rel="stylesheet" href="css/Mypage.css">
    <meta charset="UTF-8">
    <title>마이페이지</title>
</head>
<body>
<h1>마이페이지</h1>
<p>안녕하세요, <?php echo htmlspecialchars($_SESSION['user_name'] ?? '사용자'); ?>님!</p>

<ul>
    <a href="my_bookmarks.php"><button>내 북마크 목록</button></a>
    <a href="my_likes.php"><button>내 좋아요 목록</button></a>
    <a href="my_reviews.php"><button>내가 쓴 후기</button></a>
    <a href="rooms_list.php"><button>자취방 전체 목록</button></a>
    <a href="logout.php"><button>로그아웃</button></a>
    <a href="main.php"><button>메인으로 돌아가기</button></a>
    <a href="withdraw.php" style="color:red;"><button>회원 탈퇴</button></a>
</ul>
</body>
</html>
