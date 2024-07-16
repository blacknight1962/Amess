<?php
include(__DIR__ . '/../../db.php');
include('include/header.php');

if (isset($_POST['submit'])) {
  $ep_id = $_POST['ep_id'];
  $devision = $_POST['division'];
  $ep_name = $_POST['ep_name'];
  $J_level = $_POST['J_level'];
  $Mphone = $_POST['Mphone'];
  $address = $_POST['ep_addr'];
  $gender = $_POST['gender'];
  $acc_right = $_POST['acc_right'];

  $sql = "INSERT INTO employee (ep_id, division, ep_name, J_level, ep_addr, Mphone, gender, acc_right) 
  VALUES ('$ep_id', '$devision', '$ep_name','$J_level','$address', '$Mphone', '$gender','$acc_right')";

  $result = mysqli_query($conn, $sql);

  if ($result) {
    header("location: login_edit.php?msg=새로운 사용자가 등록되었습니다~!!");
  } else {
    echo "저장에 문제가 있습니다. 관리자에게 연락하십시오" . mysqli_error($conn);
  }
}
?>

<body class='bg-primary bg-opacity-10'>
  <div class='container mt-1'>
    <div class='row justify-content-center'>
      <div class='col-md-12'>
        <section class="w-75 m-auto shadow-lg p-2 my-4 rounded-4 container text-center">
          <div class="container">
            <div class="text-center mb-4">
              <h4>신규 사용자 등록</h4>
              <p class="text-muted" style='font-size: .75rem'>아래 양식에 따라 적정한 데이터를 입력 해주시오</p>
            </div>

            <div class="container d-flex justify-content-center" style='font-size: .75rem'>
              <form action="" method="post" style="width:40vw; min-width:400px;">
                <div class="row mb-3">
                  <div class="col">
                    <label class="form-label">아이디:</label>
                    <input type="text" class="form-control" name="ep_id" placeholder="직원번호">
                  </div>

                  <div class="col">
                    <label class="form-label">부 서:</label>
                    <input type="text" class="form-control" name="division" placeholder="부서">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col">
                    <label class="form-label">성 명:</label>
                    <input type="text" class="form-control" name="ep_name" placeholder="이름">
                  </div>

                  <div class="col">
                    <label class="form-label">직급:</label>
                    <input type="text" class="form-control" name="J_level" placeholder="직급">
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col">
                    <label class="form-label">연락처:</label>
                    <input type="text" class="form-control" name="Mphone" placeholder="010-0000-0000">
                  </div>

                  <div class="col form-group mt-4 mb-3">
                    <label>성별:</label> &nbsp;
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="male" value="male">
                    <label for="male" class="form-input-label">Male</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="female" value="female">
                    <label for="female" class="form-input-label">Female</label>
                  </div>
                </div>

                <div class="ep_addr">
                  <label class="form-label">주소:</label>
                  <input type="text" class="form-control" name="ep_addr" placeholder="주소">
                </div>

                <div class="acc_right">
                  <label class="form-label">보안등급:</label>
                  <input type="int" class="form-control" name="acc_right" placeholder="1~10">
                </div>

                <div bm-3>
                  <button type="submit" class="btn btn-outline-success mt-3" name="submit" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .65rem;">저장</button>
                  <a href="login_edit.php" class="btn btn-outline-danger mt-3" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .65rem;">취소</a>
                </div>
              </form>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</body>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</html>