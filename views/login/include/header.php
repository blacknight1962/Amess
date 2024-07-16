<?php include('../../db.php');
session_start();
$user_id = $_SESSION['ss_id'];

$sql_name = "SELECT * FROM employee WHERE ep_id='$user_id'";
$result = mysqli_query($conn, $sql_name);
$row = mysqli_fetch_array($result);
$user_name = htmlspecialchars($row['ep_name']);

if (!isset($_SESSION['ss_id']) or $_SESSION['ss_id'] == '') {
  echo "<script> 
  alert('로그인 후 사용가능합니다');
  self.location.href='login/login.php';
  </script>";
  exit();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Font Awesome CSS 로컬 파일 참조 -->
<link rel="stylesheet" href="/practice/AMESystem/public/vendor/fontawesome/css/all.min.css">
<link rel="stylesheet" href="/practice/AMESystem/public/vendor/bootstrap/css/bootstrap.min.css">
<script src="/practice/AMESystem/public/vendor/jquery/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="/practice/AMESystem/public/css/style.css">
<link rel="stylesheet" href="/practice/AMESystem/views/login/css/style_login.css">




  <title>AMESS 설정</title>

  <header id='navbar'>
    <div class='home_logo'>
      <div class='title'><a href='../views/main_index.php'>Amess Management System</a></div>
    </div>
    <div id='menu'>
      <ul>
        <li><a href='login_edit.php'>사용자 관리</a></li>
        <li><a href='regist_customer.php'>고객관리</a></li>
      </ul>
    </div>
    <div>
      <button type="button" class="btn btn-outline-primary btn-sm" style='font-size: 14px; font-weight:400;'><?= $user_name ?> 님</button>
      <a class="btn btn-outline-success btn-sm" style='font-size: 14px; font-weight:400' href="logout.php" role="button">로그아웃</a>
    </div>
  </header>
  </header>
</head>