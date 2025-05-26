<?php
session_start();

// 모든 세션 변수 제거
$_SESSION = array();

// 세션 쿠키가 있으면 삭제
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 세션 자체 파괴
session_destroy();

// 로그아웃 후 이동
header("Location: login.php");
exit;
?>
