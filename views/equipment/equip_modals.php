<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('../../db.php');

?>
<link rel="stylesheet" href="css/style_equip.css">
<!--신규장비 등록 Modal -->
<div class="modal fade custom-modal-size" id="insertdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="insertdata" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fs-5" id="insertdata" style="text-align: center;">신규장비 등록</h6>
        <button type="button" class="btn-close" style="font-size: 0.65rem;" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <section class="shadow-lg mt-1 p-1 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
        <div class='container-fluid' style='width: 1800px me-0 ms-0'>
        <form action='equip_process.php' method="POST">
          <div class="modal-body">
          <table class='table table-bordered mt-1' style="width: 100%; font-size: .75rem;">
            <thead style="max-width: 1800px; text-align: center;">
                  <tr class='table table-secondary'>
                    <th style="width: 5%;">No</th>
                    <th style="width: 5%;">부서</th>
                    <th style="width: 12%;">장비명</th>
                    <th style="width: 20%;">모델명</th>
                    <th style="width: 10%;">등록일자</th>
                    <th style="width: 10%;">고객명</th>
                    <th style="width: 10%;">공급사</th>
                    <th style="width: 12%;">공정</th>
                    <th style="width: 13%;">특기사항</th>
                  </tr>
                </thead>
                <tbody>
                  <tr style="font-size: .75rem;">
                    <td><input type='text' class='form-control' name='e_no' value="<?php echo $equip_num ?>" required></td>
                    <td><?= createSelectPicb1($conn, 'division', 'picb', 'picb', 'picb'); ?></td>
                    <td><input type='text' class='form-control' name='equip' placeholder="장비명" required></td>
                    <td><input type='text' class='form-control' name='model_p' placeholder="모델명" required></td>
                    <td><input type='date' class='form-control' name='regi_date' value="<?php echo date('Y-m-d') ?>" required></td>
                    <td><?= createSelectCustomer1($conn, 'customers', 'customer_na', 'customer_na', 'customer_na'); ?></td>
                    <td><input type='text' class='form-control' name='supplyer' placeholder="공급사"></td>
                    <td><input type='text' class='form-control' name='process_p' placeholder="공정"></td>
                    <td><input type='text' class='form-control' name='specif' placeholder="특기사항"></td>
                  </tr>
                </tbody>
            </table>
          </div>
        </section>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" style="font-size: .65rem;" data-bs-dismiss="modal">Close</button>
          <button type="submit" name='save_data' class="btn btn-primary btn-sm" style="font-size: .65rem;">SAVE</button>
        </div>
      </form>
    </div>
  </div>
</div>
