<?php
session_start();
include('include/header.php');
include('../public/Selection_kit.php');
include('../../db.php');
?>

<!--insert Modal -->
<div class="modal fade" id="insertdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="insertdata" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="insertdata">신규장비 등록</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action='process_equip.php' method="POST">
        <div class="modal-body">

          <div class='form-floating mb-1'>
            <input type='text' class='form-control' name='codeg' placeholder="코드그룹">
            <label for="floatingInput">코드그룹</label>
          </div>

          <div class='form-floating mb-1'>
            <input type='text' class='form-control' name='q_system' placeholder="장비명" required>
            <label for="floatingInput">장비명</label>
          </div>

          <div class='form-floating mb-1'>
            <input type='text' class='form-control' name='model' placeholder="모델명" required>
            <label for="floatingInput">모델명</label>
          </div>

          <div class='form-floating mb-1'>
            <input type='text' class='form-control' name='eqip_ver' placeholder="버젼" required>
            <label for="floatingInput">버젼</label>
          </div>

          <div class='form-floating mb-1'>
            <input type='text' class='form-control' name='pic' placeholder="등록자" required>
            <label for="floatingInput">등록자</label>
          </div>

          <div class='form-group mb-1'>
            <label for="" name='reg_date'>등록일자</label>
            <?php echo date('Y-m-d') ?>
          </div>

          <div class='form-floating mb-1'>
            <input type='text' class='form-control' name='jobcode_specifi' placeholder="비고">
            <label for="floatingInput">비고</label>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name='save_data' class="btn btn-primary">SAVE</button>
          </div>
      </form>
    </div>
  </div>
</div>