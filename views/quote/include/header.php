<?php
include(__DIR__ . '/../../../db.php');

$user_id = $_SESSION['ss_id'];

$sql_name = "SELECT * FROM employee WHERE ep_id='$user_id'";
$result = mysqli_query($conn, $sql_name);
$row = mysqli_fetch_array($result);
$user_name = htmlspecialchars($row['ep_name']);

  if (!isset($_SESSION['ss_id']) or $_SESSION['ss_id'] == '') {
    echo "<script> 
    alert('로그인 후 사용가능합니다');
    self.location.href='/practice/AMESystem/views/login/login.php';
    </script>";
    exit();
  }

  // $pageTitle 변수가 정의되지 않았을 때 기본값 설정
if (!isset($pageTitle)) {
    $pageTitle = "Default Title";
}
?>
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
<link rel="stylesheet" href="/practice/AMESystem/views/quote/css/style_quote.css">


<title><?php echo htmlspecialchars($pageTitle); ?></title>
  
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  
</head>
<body>
  <header id='navbar'>
    <div class='home_logo'>
      <div class='title'><a href='/practice/AMESystem/views/main_index.php'>Amess Management System</a></div>
    </div>

    <div>
      <button type="button" class="btn btn-outline-primary btn-sm" style='font-size: 14px; font-weight:400;'><?= $user_name ?> 님</button>
      <a class="btn btn-outline-success btn-sm" style='font-size: 14px; font-weight:400; color: #FFFFF0;' href="/practice/AMESystem/views/login/logout.php" role="button">로그아웃</a>
    </div>
  </header>

