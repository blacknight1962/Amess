<!--작업 Update Modal -->
<div class="modal fade" id="updatemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updatemodalLabel" aria-hidden="true">
  <div class="modal-dialog custom-modal-size">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="updatemodalLabel">작업 추가</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

        <div class="modal-body">
          <section class="shadow-lg mt-1 p-1 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
            <div class='container-fluid' style='width: 1800px me-0 ms-0'>
            <form id="modalForm" action="task_process.php" method="post">
                <input type="hidden" name="action" value="save">
                <input type="hidden" id="modalSeriNo" name="seri_no">
                <table class='table table-bordered mt-1 small-font' style="width: 100%;">
                  <thead style="max-width: 1800px; text-align: center;">
                    <tr class='table table-secondary'>
                      <th style="width: 4%;">No</th>
                      <th style="width: 8%;">작업일자</th>
                      <th style="width: 6%;">담당자</th>
                      <th style="width: 6%;">구분</th>
                      <th style="width: 7%;">항목</th>

                      <th style="width: 14%;">작업내용</th>
                      <th style="width: 24%;">세부내용</th>
                      <th style="width: 19%;">특기사항</th>
                      <th style="width: 7%;">가동상황</th>
                      <th style="width: 5%;"><button type="button" class="btn btn-success" style="font-size: .65rem"
                    onclick="TU_BtnAdd()">+</button></th>
                </tr>
              </thead>
              <tbody class='small-font' id='TU_Body' style="width: 1800px;">
                <?php
                if (isset($_GET['seri_no'])) {
                    $seri_no = $_GET['seri_no'];
                    $sql_data = "SELECT * FROM task_manage WHERE seri_no = '$seri_no'";
                    $result_data = mysqli_query($conn, $sql_data);
                    if (mysqli_num_rows($result_data) > 0) {
                        while ($row_data = mysqli_fetch_array($result_data)) {
                        $filtered = array(
                        'seri_no' => htmlspecialchars($row_data["seri_no"]),
                        't_no' => htmlspecialchars($row_data["t_no"]),
                        'date_task' => htmlspecialchars($row_data["date_task"]),
                        'task_person' => htmlspecialchars($row_data["task_person"]),
                        'task_aparts' => htmlspecialchars($row_data["task_aparts"]),
                        'task_part' => htmlspecialchars($row_data["task_part"]),
                        'task_title' => htmlspecialchars($row_data["task_title"]),
                        'task_content' => htmlspecialchars($row_data["task_content"]),
                        'specification' => htmlspecialchars($row_data["specification"]),
                        'manage_stat' => htmlspecialchars($row_data["manage_stat"]),
                        );
                          }

            ?>
                    <tr class='small-font' id='TU_Row'>
                      <td><input type="text" class="t_no form-control" style="text-align: center;" name="t_no[]" value="<?= $filtered["t_no"]; ?>"></td>
                      <td><input type="date" class="form-control date_task" name="date_task[]" value="<?= $filtered["date_task"]; ?>"></td>
                      <td><input type="text" id="username" class="form-control task_person" name="task_person[]" value="<?= $filtered["task_person"]; ?>"></td>
                      <td><?= createSelectOptions($conn, 'apart', 'pa_no', 'apart', 'task_aparts'); ?></td>
                      <td><?= createSelectTaskPart($conn, 'task_part', 'tp_no', 'taskpart', 'task_part'); ?></td>
                      <td><textarea class="form-control task_title" name="task_title[]" rows="1"><?= $filtered["task_title"]; ?></textarea></td>
                      <td><textarea class="form-control task_content" name="task_content[]" rows="1"><?= $filtered["task_content"]; ?></textarea></td>
                      <td><input type="text" class="form-control specification" name="specification[]" value="<?= $filtered["specification"]; ?>"></td>
                      <td><select class="form-select" name="manage_stat[]">
                          <option value="정상작동" <?= $filtered['manage_stat'] == '정상작동' ? 'selected' : ''; ?>>정상가동</option>
                          <option value="수리진행" <?= $filtered['manage_stat'] == '수리진행' ? 'selected' : ''; ?>>수리진행</option>
                          <option value="작업대기" <?= $filtered['manage_stat'] == '작업대기' ? 'selected' : ''; ?>>작업대기</option>
                          <option value="장비점검" <?= $filtered['manage_stat'] == '장비점검' ? 'selected' : ''; ?>>장비점검</option>
                          <option value="개조진행" <?= $filtered['manage_stat'] == '개조진행' ? 'selected' : ''; ?>>개조진행</option>
                          <option value="기타사항" <?= $filtered['manage_stat'] == '기타사항' ? 'selected' : ''; ?>>기타사항</option>
                          </select>
                      </td>
                      <td>
                        <button type="button" class="btn link-danger small-btn" onclick="BtnDel(this)">
                          <i class="fa-solid fa-trash fs-6"></i>
                        </button>
                      </td>
                    </tr>
            <?php   }
                
              }
            
            ?>
              </tbody>
            </table>
            <div>
              <!-- 모달 내부에 항목 추가 버튼 추가 -->
                <button type="button" class="btn btn-primary" onclick="$('#addOptionModal').modal('show');">항목 추가</button>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
            </div>
          </section>

        </div>

    </div>
  </div>
</div>