<?php
include('../db.php');

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
  <script src="https://kit.fontawesome.com/49f96f1a0f.js" crossorigin="anonymous"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Wallpoet&display=swap" rel="stylesheet">
  <link href="http://fonts.googleapis.com/css?family=Open+sans:400,300" rel='stylesheet' type='text/css'>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="../style.css">
  <style>
    body {
      font-family: "Helvetica Nene", Helvetica, Arial, "malgun gothic", sans-serif;
    }
  </style>
  <?php include('../db.php'); ?>


  <title>AMESS 장비관리</title>

  <header id='navbar'>
    <div class='title'><a href='../Main.php'>Amess Management System</a></div>
    <div id='menu'>
      <ul>
        <li><a href='#'>견적관리</a>
          <ul>
            <li><a href='../quote/quot_index.php'>견적관리</a></li>

          </ul>
        </li>
        <li><a href='jobcode_index.php'>작업코드</a>
          <ul>
            <li><a href='jobcode_index.php'>그룹등록</a></li>
            <li><a href='search_jobcode.php'>검 색</a></li>
            <li><a href='#'>상세정보</a></li>
          </ul>
        </li>
        <li><a href='../Order/order_index.php'>영업관리</a>
          <ul>
            <li><a href='../Order/order_index.php'>발주관리</a></li>
            <li><a href='../Order/sales_view.php'>매출관리</a></li>
            <li><a href='../Order/manufact_view.php'>생산관리</a></li>
          </ul>
        </li>
        <li><a href='../equipment/equip_index.php'>장비관리</a>
          <ul>
            <li><a href='../equipment/equip_index.php'>장비관리</a></li>
            <li><a href='../equipment/search_equip.php'>검색</a></li>

          </ul>
        </li>
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
</head>

<body>