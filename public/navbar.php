<?php
include('../db.php');
session_start();

if (!isset($_SESSION['ss_id']) || $_SESSION['ss_id'] == '') {
  echo "<script> 
  alert('로그인 후 사용가능합니다');
  self.location.href='login/login.php';
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
  <title>AmessManagementSystem</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="Main.php">
        <img src="img/amess_logo.png" alt="Amess Management System" width="100" height="20">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link" href="quote/quote_index.php">견적관리</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="jobcode/jobcode_index.php">작업코드</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="salesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              영업관리
            </a>
            <ul class="dropdown-menu" aria-labelledby="salesDropdown">
              <li><a class="dropdown-item" href="Order/order_index.php">발주관리</a></li>
              <li><a class="dropdown-item" href="Order/sales_index.php">매출관리</a></li>
              <li><a class="dropdown-item" href="Order/manufact_view.php">생산관리</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="equipment/equip_index.php">장비관리</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              설정
            </a>
            <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
              <li><a class="dropdown-item" href="login/login_edit.php">사용자 관리</a></li>
              <li><a class="dropdown-item" href="login/regist_customer.php">고객관리</a></li>
            </ul>
          </li>
        </ul>
        <div class="d-flex">
          <button type="button" class="btn btn-outline-primary me-2" style="font-size: 14px; font-weight: 400;"><?= $user_name ?> 님</button>
          <a class="btn btn-outline-primary" style="font-size: 14px; font-weight: 400;" href="logout.php" role="button">로그아웃</a>
        </div>
      </div>
    </div>
  </nav>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>