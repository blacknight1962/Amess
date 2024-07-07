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
  <script src="https://kit.fontawesome.com/49f96f1a0f.js" crossorigin="anonymous"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Wallpoet&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- jQuery 최신 버전 (3.7.1) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <link rel="stylesheet" href="/practice/AMESystem/public/css/style.css">
  <link rel="stylesheet" href="css/style_jobcode.css">
  <style>
    body {
      font-family: "Helvetica Nene", Helvetica, Arial, "malgun gothic", sans-serif;
    }
  </style>

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