<?php
$url = parse_url(getenv("DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$database = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("DB 접속 실패: " . $conn->connect_error);
}

echo "DB 접속 성공!";
?>