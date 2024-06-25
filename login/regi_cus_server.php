<?php
include('../db.php');

if (
  isset($_POST['custo_name']) && ($_POST['type_cu'])
) {

  $custo_name = mysqli_real_escape_string($conn, $_POST['custo_name']);
  $custo_type = mysqli_real_escape_string($conn, $_POST['type_cu']);
  $specification = mysqli_real_escape_string($conn, $_POST['specification']);

  if (empty($custo_name)) {
  } else if (empty($custo_type)) {
    header('location:regist_customer.php?error=고객 타입 선택 문제가 있습니다');
    exit();
  } else {

    $regi_date = date('Y-m-d');


    $Duple_check = "SELECT * FROM customers WHERE custo_name = '$custo_name'";
    $order = mysqli_query($conn, $Duple_check);

    if (mysqli_num_rows($order) > 0) {
      header('location:regist_customer.php?error=이 고객은 DB에 이미 존재합니다');
      exit();
    } else {
      $sql_save = "INSERT INTO customers (custo_name, custo_type, regi_date, specification)
values('$custo_name', '$custo_type','$regi_date', '$specification')";

      $result = mysqli_query($conn, $sql_save);
      if ($result) {
        header('location: regist_customer.php?error=저장에 실패했습니다.');
        exit();
      } else {
        header('location: regist_customer.php?success=성공적으로 저장했습니다.');
        exit();
      }
    }
  }
} else {
  header('location:regist_customer.php?error=문제가 발생했습니다, 관리자에게 문의 하십시오');
  exit();
}
