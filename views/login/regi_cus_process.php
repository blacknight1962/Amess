<?php
session_start();
include(__DIR__ . '/../../db.php');

/* 고객 등록 데이터 저장 */
if (isset($_POST['save_data'])) {
  $no = mysqli_real_escape_string($conn, $_POST['custo_id']);
  $customer = mysqli_real_escape_string($conn, $_POST['customer_na']);
  $type = mysqli_real_escape_string($conn, $_POST['custo_type']);
  $specification = mysqli_real_escape_string($conn, $_POST['specification']);

  $regi_date = date('Y-m-d');

  $sql = "INSERT INTO customers (custo_id, customer_na, custo_type, regi_date, specification)
  value ('$no', '$customer', '$type', '$regi_date', '$specification')";

  $result = mysqli_query($conn, $sql);

  if ($result) {
    $_SESSION['status'] = "성공적으로 데이터를 저장했습니다.";
    header('location: regist_customer.php');
  } else {
    $_SESSION['status'] = "데이터 저장에 실패했습니다.";
    header('location: regist_customer.php');
  }
}

/* 고객 정보 edit data */

/* 고객정보 UPdate data */
if (isset($_POST['update_btn'])) {
  $no = mysqli_real_escape_string($conn, $_POST['custo_id']);
  $customer = mysqli_real_escape_string($conn, $_POST['customer_na']);
  $type = mysqli_real_escape_string($conn, $_POST['custo_type']);
  $regi_date = mysqli_real_escape_string($conn, $_POST['regi_date']);
  $specification = mysqli_real_escape_string($conn, $_POST['specification']);

  $sql = "UPDATE customers SET custo_id = '$no', customer_na = '$customer', 
  custo_type = '$type', regi_date = '$regi_date', specification = '$specification' 
  WHERE custo_id = '$no'";

  $result = mysqli_query($conn, $sql);

  if ($result) {
    $_SESSION['status'] = "성공적으로 데이터를 저장했습니다.";
    header('location: regist_customer.php');
  } else {
    $_SESSION['status'] = "데이터 저장에 실패했습니다.";
    header('location: regist_customer.php');
  }
}

/* 고객 정보 Delete data */
if (isset($_GET["id"])) {

  $id = $_GET["id"];


  $sql = "DELETE FROM customers WHERE custo_id = '$id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    $_SESSION['status'] = "성공적으로 데이터를 삭제했습니다.";
    header('location: regist_customer.php');
  } else {
    $_SESSION['status'] = "데이터 삭제 하지 못했습니다.";
    header('location: regist_customer.php');
  }
} else {
  echo 'bad data of bad guy~';
}
