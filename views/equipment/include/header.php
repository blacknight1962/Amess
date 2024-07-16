<?php
include(__DIR__ . '/../../../db.php');
include(__DIR__ . '/../../../public/Selection_kit.php');
// include('task_add_modal.php'); 

$user_id = $_SESSION['ss_id'];

$sql_name = "SELECT * FROM employee WHERE ep_id='$user_id'";
$result = mysqli_query($conn, $sql_name);
$row = mysqli_fetch_array($result);
$user_name = htmlspecialchars($row['ep_name']);

// 특정 페이지에서 리다이렉트를 건너뛰기 위한 조건 추가
if (!isset($_SESSION['ss_id']) or $_SESSION['ss_id'] == '') {
    if (!isset($_GET['skip_redirect']) || $_GET['skip_redirect'] != 'true') {
        echo "<script> 
        alert('로그인 후 사용가능합니다');
        self.location.href='/practice/AMESystem/views/login/login.php';
        </script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/practice/AMESystem/public/vendor/fontawesome/css/all.min.css">
<link rel="stylesheet" href="/practice/AMESystem/public/vendor/bootstrap/css/bootstrap.min.css">
<script src="/practice/AMESystem/public/vendor/jquery/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="/practice/AMESystem/public/css/style.css">
<link rel="stylesheet" href="/practice/AMESystem/views/equipment/css/style_equip.css">
  
  <title>AMESS 장비관리</title>
</head>
<body>
  <header id='navbar'>
    <div class='home_logo'>
      <div class='title'><a href='../Main.php'>Amess Management System</a></div>
    </div>
    <div id='menu'>
      <ul>
        <li><a href='equip_index.php'>장비관리</a></li>
        <li><a href='facility_index.php'>설비관리</a></li>
        <li><a href='task_index.php'>작업관리</a></li>
        <li><a href='task_search.php'>통합검색</a></li>
      </ul>
    </div>
    <div>
      <button type="button" class="btn btn-outline-primary btn-sm" style='font-size: 14px; font-weight:400;'><?= $user_name ?> 님</button>
      <a class="btn btn-outline-success btn-sm" style='font-size: 14px; font-weight:400' href="logout.php" role="button">로그아웃</a>
    </div>
  </header>

