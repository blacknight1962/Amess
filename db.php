<?php
// $url = parse_url(getenv("DATABASE_URL"));

$server = "localhost";
$username = "root";
$password = "3159424";
$database = "amess";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("DB 접속 실패: " . $conn->connect_error);
}

// echo "DB 접속 성공!";
?>