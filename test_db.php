<?php

// 에러 보고 활성화
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 데이터베이스 연결 설정
$url = parse_url(getenv("DATABASE_URL"));
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$database = 'amess'; // amess 데이터베이스 사용

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("DB 접속 실패: " . $conn->connect_error);
}

// 테이블 존재 여부 확인
$sql = "SHOW TABLES LIKE 'pw_board'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "테이블 'pw_board'가 존재합니다.<br>";
} else {
    echo "테이블 'pw_board'가 존재하지 않습니다.<br>";
}

// 테이블 내용 확인
$sql = "SELECT * FROM pw_board";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - PW: " . $row["pw"] . "<br>";
    }
} else {
    echo "쿼리 실패: " . $conn->error;
}

$conn->close();
?>