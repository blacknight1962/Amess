<?php
$servername = "localhost";
$username = "root";
$password = "3159424";
$database = "amess";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
die("DB 접속 실패: " . mysqli_connect_error());
}
?>