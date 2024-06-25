<?php
include "../../db.php";

/* User Delete data */
if (isset($_GET["id"])) {

  $id = $_GET["id"];

  $sql = "DELETE FROM employee WHERE ep_id = '$id'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    $_SESSION['status'] = "성공적으로 데이터를 삭제했습니다.";
    header('location: login_edit.php');
  } else {
    $_SESSION['status'] = "데이터 삭제 하지 못했습니다.";
    header('location: login_edit.php');
  }
} else {
  echo 'bad data of bad guy~';
}
