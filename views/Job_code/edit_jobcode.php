<?php
session_start();
include('include/header.php');
include('../../db.php')
?>
<link rel="stylesheet" href="css/style_jobcode.css">
<!--edit data -->
<div class='"bg-success bg-opacity-20"'>
  <section class="w-75 m-auto shadow-lg p-2 my-4 rounded-3 container text-center justify-content-center">
    <div class="row justify-content-center ">
      <div class="col-sm-6 ">
        <div class="card bg-secondary">
          <div class="card header">
            <h4 class="fs-5" style="width:513px" id="insertdata">작업코드 편집</h4>
          </div>
          <div class='card-body'>
            <?php if (isset($_GET['id'])) {
              $id = $_GET['id'];

              $sql = "SELECT * FROM jobcode WHERE j_no=$id";
              $result = mysqli_query($conn, $sql);
              if (mysqli_num_rows($result) > 0) {

                foreach ($result as $row) { ?>
                  <form action='process_jobcode.php' method='POST'>
                    <div class="form-floating mb-1">
                      <input type='text' class='form-control' name='j_no' id='j_no' value='<?php echo $row['j_no'] ?>' placeholder="No">
                      <label for="floatingInput">No</label>
                    </div>

                    <div class="form-floating mb-1">
                      <input type='text' class='form-control' name='seri_no' value='<?php echo $row['seri_no'] ?>' placeholder="부서명">
                      <label for="floatingInput">코드그룹</label>
                    </div>

                    <div class='form-floating mb-1'>
                      <input type='text' class='form-control' name='equip' value='<?php echo $row['equip'] ?>' placeholder="장비명" required>
                      <label for="floatingInput">장비명</label>
                    </div>

                    <div class='form-floating mb-1'>
                      <input type='text' class='form-control' name='model_p' value='<?php echo $row['model_p'] ?>' placeholder="모델명" required>
                      <label for="floatingInput">모델명</label>
                    </div>

                    <div class='form-floating mb-1'>
                      <input type='text' class='form-control' name='equip_ver' value='<?php echo $row['equip_ver'] ?>' placeholder="버젼">
                      <label for="floatingInput">버젼</label>

                    </div>
                    <div class='form-floating mb-1'>
                      <input type='text' class='form-control' name='pic' value='<?php echo $row['pic'] ?>' placeholder="작성자">
                      <label for="floatingInput">작성자</label>
                    </div>
                    <div class='form-floating mb-1'>
                      <input type='text' class='form-control' name='regi_date' value='<?php echo $row['regi_date'] ?>' placeholder="등록일자">
                      <label for="floatingInput">등록일자</label>
                    </div>

                    <div class='form-floating mb-1'>
                      <input type='text' class='form-control' name='jobcode_specifi' value='<?php echo $row['jobcode_specifi'] ?>' placeholder="특기사항">
                      <label for="floatingInput">특기사항</label>
                    </div>
          </div>
          <div class="footer">
            <button type="submit" name='update_btn' class="btn btn-info btn-sm" btn-sm>UPDATE</button>
            <a class='btn btn-outline-danger btn-sm ms-2 me-1' href="jobcode_index.php" role="button">취소</a>
          </div>
          </form>
    <?php }
              } else {
                echo "데이터베이스에서 해당 데이터를 찾지 못했습니다.";
              }
            } else {
              echo "시스템에 문제가 있습니다. 관리자에게 신고하십시오~ㅠ";
            }
    ?>
        </div>
      </div>
    </div>
  </section>
</div>