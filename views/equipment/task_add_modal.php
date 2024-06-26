<?php
include ('../../db.php');

$user_name = $_SESSION['username'];  // 
$date = $_SESSION['date'];  // 세션에서 날짜 가져오기

$seri_no = isset($_POST['seri_no']) ? $_POST['seri_no'] : 'default_value';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>작업 추가</title>
    <link rel="stylesheet" href="css/style_equip.css"> <!-- CSS 파일을 링크로 포함 -->
</head>
<body>
<!-- 작업 상세 정보 등록 -->
<div class="modal fade custom-modal-size" id="addTaskModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addTaskmodallabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fs-5" style="text-align: center;" id="addTaskModallabel">작업 추가</h6>
        <button type="button" class="btn-close btn-sm" style="font-size: .65rem;" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <section class="shadow-lg mt-1 p-1 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
        <div class='container-fluid' style='width: 1850px me-0 ms-0'>
          <form id="saveTask" action="task_process.php" method="POST">
            <input type="hidden" name="action" value="saveTask">
            <input type="hidden" id="seri_no" name="seri_no" value="<?= $seri_no ?>">
              <div class="modal-body">
                <table class='table table-custom table-bordered mt-1' style="font-size: .65rem; width: 100%;">
                  <thead style="max-width: 1850px; text-align: center;">
                    <tr class='table table-secondary'>
                      <th style="width: 4%;">No</th>
                      <th style="width: 8%;">작업일자</th>
                      <th style="width: 6%;">담당자</th>
                      <th style="width: 6%;">구분</th>
                      <th style="width: 8%;">항목</th>
                      <th style="width: 14%;">작업내용</th>
                      <th style="width: 27%;">세부내용</th>
                      <th style="width: 16%;">특기사항</th>
                      <th style="width: 6%;">가동상황</th>
                      <th style="width: 5%;">
                        <button type="button" class="btn btn-success btn-sm" style="font-size: .65rem" onclick="M_BtnAdd()">+</button>
                      </th>
                  </thead>
                  <tbody id="TU_Body">
                    <tr id="TU_Row" style="font-size: .65rem;">
                      <td><input type="text" class="form-control t_no" style="text-align: center;" name="t_no[]" value="1"></td>
                      <td><input type="text" class="form-control date_task" style="font-size: .65rem" name="date_task[]" value="<?= $_SESSION['date'] ?? '' ?>"></td>
                      <td><input type="text" class="form-control task_person" style="font-size: .65rem" name="task_person[]" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>"></td>
                      <td><?= createSelectTask($conn, 'task_aparts', 'taskaparts', 'taskaparts', 'task_aparts[]', '') ?></td>
                      <td><?= createSelectTaskPart($conn, 'task_part', 'hangmok', 'hangmok', 'hangmok[]', '') ?></td>
                      <td><textarea class="form-control task_title" name="task_title[]" rows="1" style="font-size: .65rem; resize: none; width: 100%; height: 65px;"></textarea></td>
                      <td><textarea class="form-control task_content" name="task_content[]" rows="1" style="font-size: .65rem; resize: none; width: 100%; height: 65px;"></textarea></td>
                      <td><input type="text" class="form-control specification" style="font-size: .65rem; width: 100%; height: 65px;" name="specification[]" value=''></td>
                      <td><?= createSelectStatus($conn, 'manage_stat', 'status', 'status', 'manage_stat[]', '') ?></td>
                      <td><button type="button" class="btn link-danger small-btn" style="font-size: .65rem" onclick="BtnDelTU(this)">
                          <i class="fa-solid fa-trash fs-6"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary small-btn" style="font-size: .65rem" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name='save_data' class="btn btn-primary small-btn" style="font-size: .65rem">SAVE</button>
                  </div>
              </div> <!--modal body -->
          </form>
        </div>
      </section>

    </div>
  </div>
</div>
