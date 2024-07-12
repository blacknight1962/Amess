<?php
include(__DIR__ . '/../../db.php');

// 쿼리 실행
$sql = "SELECT * FROM task_part ORDER BY tp_no DESC";
$result = mysqli_query($conn, $sql);

// 초기화
$part_no = '1'; // 기본값 설정
$hangmok_list = [];

// 결과 처리
if(mysqli_num_rows($result) > 0){
  $row = mysqli_fetch_assoc($result);
  $part_no = $row['tp_no'] + 1; // 새로운 tp_no 계산
  mysqli_data_seek($result, 0); // 결과 포인터를 처음으로 되돌림
  while($row = mysqli_fetch_assoc($result)){
    $hangmok_list[] = $row;
  }
}
?>
<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        저장이 성공적으로 완료되었습니다.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['success']) && $_GET['success'] == '0'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        저장 중 오류가 발생했습니다.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<!-- Modal -->
<div class="modal fade" id="addOptionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog"> <!-- 모달 크기를 크게 설정 -->
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fs-6" id="staticBackdropLabel">새 항목 추가</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="max-height: 400px; overflow-y: auto;"> <!-- 스크롤 추가 -->
        <form id="add_Task_Part" action="task_parts_save.php" method="POST">
          <input type="hidden" name="add_task_part" value="1">
          <input type="text" class="form-control" style="margin-bottom: 5px; font-size: .75rem" name="tp_no" placeholder="항목번호" value="<?= htmlspecialchars($part_no) ?>">
          <input type="text" class="form-control" style="margin-bottom: 5px; font-size: .75rem" name="hangmok" placeholder="추가 할 항목명">        
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" style="font-size: .65rem" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm" style="font-size: .65rem">저장</button>
          </div>
        </form>
        <table class="table table-bordered table-striped" style="font-size: .75rem">
          <thead>
            <tr>
              <th>항목번호</th>
              <th>항목명</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($hangmok_list as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['tp_no']) ?></td>
                <td><?= htmlspecialchars($item['hangmok']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>