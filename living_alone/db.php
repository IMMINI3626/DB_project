<?php
$host = 'localhost'; $db   = 'living_alone'; $user = 'root'; $pass = '0000';          

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}
?>