<?php
include(__DIR__ . '/../../../db.php');

if (!isset($_SESSION['ss_id']) or $_SESSION['ss_id'] == '') {
  echo "<script> 
  alert('로그인 후 사용가능합니다');
  self.location.href='../login/login.php';
  </script>";
  exit();
}

$user_id = $_SESSION['ss_id'];

$sql_name = "SELECT * FROM employee WHERE ep_id='$user_id'";
$result = mysqli_query($conn, $sql_name);
$row = mysqli_fetch_array($result);
$user_name = htmlspecialchars($row['ep_name']);
?>

<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/practice/AMESystem/public/vendor/font-awesome/css/all.min.css">
<link rel="stylesheet" href="/practice/AMESystem/public/vendor/bootstrap/css/bootstrap.min.css">
<script src="/practice/AMESystem/public/vendor/jquery/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="/practice/AMESystem/public/css/style.css">
<link rel="stylesheet" href="/practice/AMESystem/views/Job_code/css/style_jobcode.css">

  <title>AMESS 작업코드</title>

  <header id='navbar'>
    <div class='title'><a href='/practice/AMESystem/views/main.php'>Amess Management System</a></div>
    <div id='menu'>
    </div>
    <div>
      <button type="button" class="btn btn-outline-primary btn-sm" style='font-size: 14px; font-weight:400;'><?= $user_name ?> 님</button>
      <a class="btn btn-outline-success btn-sm" style='font-size: 14px; font-weight:400' href="/practice/AMESystem/views/login/logout.php" role="button">로그아웃</a>
    </div>
  </header>
</head>

<body>