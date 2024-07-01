<?php
$servername = getenv("DB_HOST");
$username = getenv("DB_USER");
$password = getenv("DB_PASSWORD");
$database = getenv("DB_NAME");

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
die("DB 접속 실패: " . mysqli_connect_error());
}
?>