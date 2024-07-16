<?php
session_start(); // 세션 시작
include(__DIR__ . '/../../../db.php');
include(__DIR__ . '/../../../public/Selection_kit.php');



if (basename($_SERVER['PHP_SELF']) != 'login.php') {
    if (isset($_SESSION['ss_id'])) {
        $user_id = $_SESSION['ss_id'];

        $sql_name = "SELECT * FROM employee WHERE ep_id='$user_id'";
        $result = mysqli_query($conn, $sql_name);
        $row = mysqli_fetch_array($result);
        $user_name = isset($row['ep_name']) ? htmlspecialchars($row['ep_name']) : 'Unknown';

        // 사용자 이름을 출력하는 로직 등
    } else {
        // 세션 ID가 없을 경우 로그인 페이지로 리다이렉트
        echo "<script>location.href='/practice/AMESystem/views/login/login.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Font Awesome CSS 로컬 파일 참조 -->
<link rel="stylesheet" href="/practice/AMESystem/public/vendor/fontawesome/css/all.min.css">
<link rel="stylesheet" href="/practice/AMESystem/public/vendor/bootstrap/css/bootstrap.min.css">
<script src="/practice/AMESystem/public/vendor/jquery/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="/practice/AMESystem/public/css/style.css">
<link rel="stylesheet" href="/practice/AMESystem/views/order/css/style_order.css"> 

  
  <title><?php echo $pageTitle; ?></title>
</head>
<body>
  <header id='navbar'>
    <div class='home_logo'>
      <div class='title'><a href='/practice/AMESystem/views/main_index.php'>Amess Management System</a></div>
    </div>
    <div id='menu'>
      <ul>
        <li><a href='/practice/AMESystem/views/order/order_index.php'>발주관리</a></li>
        <li><a href='/practice/AMESystem/views/order/sales_index.php'>매출관리</a></li>
        <li><a href='/practice/AMESystem/views/order/manufact_view.php'>생산관리</a></li>
      </ul>
    </div>
    <div>
      <button type="button" class="btn btn-outline-primary btn-sm" style='font-size: 14px; font-weight:400;'><?= $user_name ?> 님</button>
      <a class="btn btn-outline-success btn-sm" style='font-size: 14px; font-weight:400; color: #FFFFF0;' href="/practice/AMESystem/views/login/logout.php" role="button">로그아웃</a>
    </div>
  </header>