<?php
include('include/header.php');
include(__DIR__ . '/../../db.php');

$id = $_GET['id'];

if (isset($_POST['submit'])) {
  $id = $_POST['ep_id'];
  $division = $_POST['division'];
  $ep_name = $_POST['ep_name'];
  $J_level = $_POST['J_level'];
  $Mphone = $_POST['Mphone'];
  $address = $_POST['ep_addr'];
  $gender = $_POST["gender"];
  $acc_right = $_POST['acc_right'];

  $sql = "UPDATE employee SET division = '$division', 
  ep_name = '$ep_name', J_level = '$J_level', ep_addr = '$address', Mphone = '$Mphone', 
  gender='$gender', acc_right='$acc_right' WHERE ep_id = '$id'";

  $result = mysqli_query($conn, $sql);

  if ($result) {
    header("location: login_edit.php?msg= 사용자 정보가 수정되었습니다~!!");
  } else {
    echo "저장에 문제가 있습니다. 관리자에게 연락하십시오" . mysqli_error($conn);
  }
}
?>

<body class='bg-primary bg-opacity-10'>
  <div class='container mt-1'>
    <div class='row justify-content-center'>
      <div class='col-md-12'>
        <div class="container-fluid justify-content-center">
          <h4 class='bg-primary bg-opacity-10 mt-1 mb-1 p-2' style='text-align: center'>사용자 정보 수정</h4>
          <section class="w-75 m-auto shadow-lg p-2 my-4 rounded-3 container">
            <?php

            $sql = "SELECT * FROM employee WHERE ep_id = '$id' LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            ?>
            <div class="container d-flex justify-content-center">
              <form action="" method="post" style="width:50vw; min-width:300px;">
                <div class="row mb-3">
                  <div class="col">
                    <label class="form-label">아이디:</label>
                    <input type="text" class="form-control" name="ep_id" value="<?php echo $row['ep_id'] ?>">
                  </div>

                  <div class="col">
                    <label class="form-label">부 서:</label>
                    <input type="text" class="form-control" name="division" value="<?php echo $row['division'] ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col">
                    <label class="form-label">성 명:</label>
                    <input type="text" class="form-control" name="ep_name" value="<?php echo $row['ep_name'] ?>">
                  </div>

                  <div class="col">
                    <label class="form-label">직급:</label>
                    <input type="text" class="form-control" name="J_level" value="<?php echo $row['J_level'] ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col">
                    <label class="form-label">연락처:</label>
                    <input type="text" class="form-control" name="Mphone" value="<?php echo $row['Mphone'] ?>">
                  </div>

                  <div class="col form-group mb-3">
                    <label>성별:</label> &nbsp;
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="male" value="male" <?php echo ($row['gender'] == 'male') ? "checked" : ""; ?>>
                    <label for="male" class="form-input-label">Male</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="female" value="female" <?php echo ($row['gender'] == 'female') ? "checked" : ""; ?>>
                    <label for="female" class="form-input-label">Female</label>
                  </div>
                </div>

                <div class="ep_addr">
                  <label class="form-label">주소:</label>
                  <input type="text" class="form-control" name="ep_addr" value="<?php echo $row['ep_addr'] ?>">
                </div>

                <div class="acc_right">
                  <label class="form-label">보안등급:</label>
                  <input type="int" class="form-control" name="acc_right" value="<?php echo $row['acc_right'] ?>">
                </div>

                <div bm-3>
                  <button type="submit" class="btn btn-success mt-3" name="submit" style="--bs-btn-padding-y: .35rem; --bs-btn-padding-x: .6rem; --bs-btn-font-size: .65rem;">저장</button>
                  <a href="login_edit.php" class="btn btn-warning mt-3" style="--bs-btn-padding-y: .35rem; --bs-btn-padding-x: .6rem; --bs-btn-font-size: .65rem;">취소</a>
                </div>
              </form>
            </div>
        </div>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>