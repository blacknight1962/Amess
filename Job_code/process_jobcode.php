<?php
session_start();
include('../db.php');

/* 장비등록 데이터 저장 */
if (isset($_POST['save_data'])) {
  $no = mysqli_real_escape_string($conn, $_POST['j_no']);
  $seri_no = mysqli_real_escape_string($conn, $_POST['seri_no']);
  $equip = mysqli_real_escape_string($conn, $_POST['equip']);
  $model_p = mysqli_real_escape_string($conn, $_POST['model_p']);
  $equip_ver = mysqli_real_escape_string($conn, $_POST['equip_ver']);
  $pic = mysqli_real_escape_string($conn, $_POST['pic']);
  $jobcode_specifi = mysqli_real_escape_string($conn, $_POST['jobcode_specifi']);

  $regi_date = date('Y-m-d');

  $sql = "INSERT INTO jobcode (j_no, seri_no, equip, model_p, regi_date, equip_ver, pic, jobcode_specifi)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssssss", $no, $seri_no, $equip, $model_p, $regi_date, $equip_ver, $pic, $jobcode_specifi);

  if ($stmt->execute()) {
    $_SESSION['status'] = "성공적으로 데이터를 저장했습니다.";
  } else {
    $_SESSION['status'] = "데이터 저장에 실패했습니다: " . $stmt->error;
  }
  $stmt->close();
  header('location: jobcode_index.php');
}

/* 장비 UPdate data */
if (isset($_POST['update_btn'])) {
  $j_no = mysqli_real_escape_string($conn, $_POST['j_no']);
  $seri_no = mysqli_real_escape_string($conn, $_POST['seri_no']);
  $equip = mysqli_real_escape_string($conn, $_POST['equip']);
  $model_p = mysqli_real_escape_string($conn, $_POST['model_p']);
  $regi_date = mysqli_real_escape_string($conn, $_POST['regi_date']);
  $equip_ver = mysqli_real_escape_string($conn, $_POST['equip_ver']);
  $pic = mysqli_real_escape_string($conn, $_POST['pic']);
  $jobcode_specifi = mysqli_real_escape_string($conn, $_POST['jobcode_specifi']);

  $sql = "UPDATE jobcode SET seri_no = ?, equip = ?, model_p = ?, regi_date = ?, equip_ver = ?, pic = ?, jobcode_specifi = ? WHERE j_no = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssssss", $seri_no, $equip, $model_p, $regi_date, $equip_ver, $pic, $jobcode_specifi, $j_no);

  if ($stmt->execute()) {
    $_SESSION['status'] = "성공적으로 데이터를 업데이트했습니다.";
  } else {
    $_SESSION['status'] = "데이터 업데이트에 실패했습니다: " . $stmt->error;
  }
  $stmt->close();
  header('location: jobcode_index.php');
}

/* 장비 Delete data */
if (isset($_GET["id"])) {
  $id = $_GET["id"];

  $sql = "DELETE FROM jobcode WHERE j_no = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $id);

  if ($stmt->execute()) {
    $_SESSION['status'] = "성공적으로 데이터를 삭제했습니다.";
  } else {
    $_SESSION['status'] = "데이터 삭제에 실패했습니다: " . $stmt->error;
  }
  $stmt->close();
  header('location: jobcode_index.php');
} else {
  echo '잘못된 데이터입니다.';
}
?>