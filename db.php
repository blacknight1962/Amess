<?php
$servername = "localhost";
$username = "root";
$password = "3159424";
$database = "amess";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
  echo "DB 접속실패";
} 
/* else {
  echo "DB 접속 성공";
}*/
?>