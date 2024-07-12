<?php
session_start();
include(__DIR__ . '/../../db.php');

/* 장비 등록 데이터 저장 */
if (isset($_POST['save_data'])) {
  $e_no = mysqli_real_escape_string($conn, $_POST['e_no']);
  $picb = mysqli_real_escape_string($conn, $_POST['picb']);
  $equip = mysqli_real_escape_string($conn, $_POST['equip']);
  $model_p = mysqli_real_escape_string($conn, $_POST['model_p']);

  $regi_date = mysqli_real_escape_string($conn, $_POST['regi_date']);
  $customer = mysqli_real_escape_string($conn, $_POST['customer_na']);
  $supplyer = mysqli_real_escape_string($conn, $_POST['supplyer']);
  $process_p = mysqli_real_escape_string($conn, $_POST['process_p']);
  $specif = mysqli_real_escape_string($conn, $_POST['specif']);

  $sql = "INSERT INTO equipment(e_no, picb, equip, model_p, regi_date, customer, supplyer, process_p, specif)
  value ('$e_no', '$picb', '$equip', '$model_p', '$regi_date', '$customer', '$supplyer', '$process_p', '$specif')";

  $result = mysqli_query($conn, $sql);

  if ($result) {
    $_SESSION['status'] = "성공적으로 데이터를 저장했습니다.";
    header('location: equip_index.php');
  } else {
    $_SESSION['status'] = "데이터 저장에 실패했습니다.";
    header('location: equip_index.php');
  }
}

/* 장비 edit data */

/* 장비 UPdate data */
// var_dump($_POST);
if (isset($_POST['update_btn'])) {
  $e_no = mysqli_real_escape_string($conn, $_POST['e_no']);

  $picb = mysqli_real_escape_string($conn, $_POST['picb']);
  $equip = mysqli_real_escape_string($conn, $_POST['equip']);
  $model_p = mysqli_real_escape_string($conn, $_POST['model_p']);
  $regi_date = mysqli_real_escape_string($conn, $_POST['regi_date']);
  $customer = mysqli_real_escape_string($conn, $_POST['customer']);
  $supplyer = mysqli_real_escape_string($conn, $_POST['supplyer']);
  $process_p = mysqli_real_escape_string($conn, $_POST['process_p']);
  $specif = mysqli_real_escape_string($conn, $_POST['specif']);

  $sql = "UPDATE equipment SET e_no = '$e_no', picb = '$picb', 
  equip = '$equip', model_p = '$model_p', 
  regi_date = '$regi_date', customer = '$customer', 
  supplyer = '$supplyer', process_p = '$process_p',
  specif = '$specif' WHERE e_no = '$e_no'";
  $result = mysqli_query($conn, $sql);

if ($result) {
    echo "<script>
            alert('성공적으로 데이터를 저장했습니다.');
            window.onunload = function(){
                window.opener.location.href = 'equip_index.php';
            };
            window.close();
          </script>";
} else {
    echo "<script>
            alert('데이터 저장에 실패했습니다.');
            window.close();
          </script>";
}
}

/* 장비 데이터 삭제 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_btn'])) {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        // SQL을 통해 데이터 삭제
        $query = "DELETE FROM equipment WHERE e_no = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "삭제 성공";
        } else {
            echo "삭제 실패";
        }
        $stmt->close();
        $conn->close();
    } else {
        // id가 제공되지 않은 경우
        header('HTTP/1.1 400 Bad Request');
        echo "잘못된 요청입니다.";
    }
} else {
    // 삭제 버튼이 눌리지 않은 경우 아무 동작도 하지 않음
}
?>