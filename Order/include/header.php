<?php
session_start(); // 세션 시작
include('../db.php');
include('../Selection_kit.php');



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
        echo "<script>location.href='../login/login.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/49f96f1a0f.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Wallpoet&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="style_order.css?v=1.1">
 

  
  <title><?php echo $pageTitle; ?></title>
</head>
<body>
  <header id='navbar'>
    <div class='home_logo'>
      <div class='title'><a href='../Main.php'>Amess Management System</a></div>
    </div>
    <div id='menu'>
      <ul>
        <li><a href='../quote/quote_index.php'>견적관리</a></li>
        <li><a href='#'>작업코드</a>
          <ul>
            <li><a href='../job_code/jobcode_index.php'>작업코드 관리</a></li>
            <li><a href='../job_code/search_jobcode.php'>검 색</a></li>
            <li><a href='#'>상세정보</a></li>
          </ul>
        </li>
        <li><a href='#'>영업관리</a>
          <ul>
            <li><a href='order_index.php'>발주관리</a></li>
            <li><a href='sales_index.php'>매출관리</a></li>
            <li><a href='manufact_view.php'>생산관리</a></li>
          </ul>
        </li>
        <li><a href='../equipment/equip_index.php'>장비관리</a></li>
        <li><a href='#'>설정</a>
          <ul>
            <li><a href='../login/login.php'>로그인</a></li>
            <li><a href='../login/login_edit.php'>사용자 관리</a></li>
            <li><a href='../login/regist_customer.php'>고객관리</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div>
      <button type="button" class="btn btn-outline-primary btn-sm" style='font-size: 14px; font-weight:400;'><?= $user_name ?> 님</button>
      <a class="btn btn-outline-success btn-sm" style='font-size: 14px; font-weight:400' href="logout.php" role="button">로그아웃</a>
    </div>
  </header>