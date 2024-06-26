<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('include/header.php'); 
include('task_add_modal.php');
include('delete_task.php');
?>
<script>
var sessionDate = "<?= $_SESSION['date'] ?? ''; ?>";
var sessionUsername = "<?= htmlspecialchars($_SESSION['username'] ?? ''); ?>";
</script>
<?php
if (isset($_SESSION['message']) && isset($_SESSION['seri_no'])) {
    $message = $_SESSION['message'];
    $seri_no = $_SESSION['seri_no'];
    // 세션 메시지 출력 후 세션 클리어
    echo "<script>alert('$message');</script>";
    unset($_SESSION['message']);
    unset($_SESSION['seri_no']);
}
// 페이지 로드 시 세션에서 조회 파라미터 사용
if (isset($_SESSION['lastSearch'])) {
    $searchParameter = $_SESSION['lastSearch'];
    // 데이터 로드 로직
}
$dataExist = false; // 초기화

// $e_no 변수 초기화
// $e_no 변수 초기화
if (isset($_GET['e_no']) && isset($_GET['sub_no']) && isset($_GET['seri_no'])) {
    $e_no = mysqli_real_escape_string($conn, $_GET['e_no']);
    $sub_no = mysqli_real_escape_string($conn, $_GET['sub_no']);
    $seri_no = mysqli_real_escape_string($conn, $_GET['seri_no']);
} elseif (isset($_SESSION['e_no']) && isset($_SESSION['sub_no']) && isset($_SESSION['seri_no'])) {
    $e_no = mysqli_real_escape_string($conn, $_SESSION['e_no']);
    $sub_no = mysqli_real_escape_string($conn, $_SESSION['sub_no']);
    $seri_no = mysqli_real_escape_string($conn, $_SESSION['seriNo']);
} else {
    $e_no = 'undefined';
    $sub_no = 'undefined';
    $seri_no = 'undefined';
}

?>
<body>
<!-- 설비 정보 화면 -->
<div class='bg-dark bg-opacity-10' style='text-align: center;'>
  <h4 class='bg-danger bg-opacity-10 mt-1 mb-1 p-2' style='text-align: center'>장비 - 설비 - 작업관리</h4>
    <div class='row justify-content-center' style="max-width: 1550px; margin: 3px auto;">
      <section class="shadow-lg mt-2 p-2 pt-0 my-4 rounded-3 container-custom text-center justify-content-center">
        <div class='container-fluid' style='padding: 0 10px; display: flex; align-items: center; margin: 2px 2px;'>
        <!-- 검색란 -->
        <!-- 검색 버튼 -->
          <button type="button" class="btn btn-outline-primary" id="searchButton" style="font-size: .65rem; width: 10%;">Search...</button>
          <button type="button" class="btn btn-outline-primary" id="searchButton" style="font-size: .65rem; width: 6%; text-decoration: none; ">
      <a href="task_search.php" target="_blank">통합검색</a></button>
        </div>
        <table class="table table-striped table-bordered table-hover mt-1 table-xl" style='font-size: .65rem'>
          <thead class='table-warning'>
            <tr>
              <th style="width: 4%;">no</th>
              <th style="width: 4%;">부서</th>
              <th style="width: 13%;">장비명</th>
              <th style="width: 13%;">모델명</th>
              <th style="width: 8%;">S/N</th>

              <th style="width: 7%;">SW ver.</th>
              <th style="width: 7%;">가동상황</th>
              <th style="width: 6%;">납품일자</th>
              <th style="width: 6%;">고객명</th>
              <th style="width: 9%;">사업장</th>

              <th style="width: 5%;">설치라인</th>
              <th style="width: 11%;">공정</th>
              <th style="width: 7%;">고객명칭</th>
            </tr>
          </thead>
          <tbody class='' style="font-size: .65rem;">
            <?php
            // print_r($_GET);
            $sql = "SELECT e.*, f.* FROM equipment e INNER JOIN facility f ON e.e_no = f.e_no WHERE e.e_no = '$e_no' AND f.sub_no = '$sub_no'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                $filtered = array(
                  'no' => htmlspecialchars($row['e_no']),
                  'picb' => htmlspecialchars($row['picb']),
                  'equip' => htmlspecialchars($row['equip']),
                  'model_p' => htmlspecialchars($row['model_p']),
                  'seri_no' => htmlspecialchars($row['seri_no']),

                  'sw_ver' => htmlspecialchars($row['sw_ver']),
                  'manage_stat' => htmlspecialchars($row['manage_stat']),
                  'date_supply' => htmlspecialchars($row['date_supply']),
                  'customer' => htmlspecialchars($row['customer']),
                  'place_fac' => htmlspecialchars($row['place_fac']),

                  'line_no' => htmlspecialchars($row['line_no']),
                  'process_p' => htmlspecialchars($row['process_p']),
                  'custo_nick' => htmlspecialchars($row["custo_nick"]),
                  );
                    ?>
                  <tr>
                    <td><?= $filtered['no'] ?></td>
                    <td><?= $filtered['picb'] ?></td>
                    <td><?= $filtered['equip'] ?></td>
                    <td><?= $filtered["model_p"] ?></td>
                    <td><?= $filtered["seri_no"] ?></td>

                    <td><?= $filtered['sw_ver'] ?></td>
                    <td><?= $filtered['manage_stat'] ?></td>
                    <td><?= $filtered["date_supply"] ?></td>
                    <td><?= $filtered['customer'] ?></td>
                    <td><?= $filtered['place_fac'] ?></td>

                    <td><?= $filtered['line_no'] ?></td>
                    <td><?= $filtered['process_p'] ?></td>
                    <td><?= $filtered['custo_nick'] ?></td>
                  </tr>
                    <?php
                    }
            } else {
              ?>
              <tr colspan="4">No More Data</tr>
            <?php
            } ?>
          </tbody>
        </table>
      </section>
    </div>
</div>
<!-- 작업 정보 요약 버젼 화면-->
<div class='bg-dark bg-opacity-10'>
  <div class='row justify-content-center' style="max-width: 1550px; margin: 0 auto;">
    <section class="shadow-lg mt-1 p-1 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
      <table class='table table-bordered mt-1' style="font-size: .65rem; width: 100%;">
        <thead style="max-width: 1500px; text-align: center;">
          <tr class="table-warning">
            <th style="width: 4%;">No</th>
            <th style="width: 8%;">작업일자</th>
            <th style="width: 8%;">작업담당</th>
            <th style="width: 25%;">작업내용</th>
            <th style="width: 7%;">구분</th>
            <th style="width: 13%;">항목</th>
            <th style="width: 27%;">특기사항</th>
          </tr>
        </thead>
        <tbody style="width: 1550px;">
          <?php
          // print_r($_GET);
              $query = "SELECT * FROM task_manage ORDER BY t_no DESC LIMIT 30";
              $result = mysqli_query($conn, $query);
              
              if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($row['t_no']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['date_task']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['task_person']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['task_title']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['task_aparts']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['hangmok']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['specification']) . "</td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='7' style='text-align: center;'>현재 데이터가 없습니다.</td></tr>";
              }
          
          ?>
        </tbody>
      </table>
    </section>
  </div>
</div>
<!-- 작업 상세 정보 -->
<?php
$date_task = isset($_POST['date_task']) ? $_POST['date_task'] : date('Y-m-d');
if (isset($seri_no) && $seri_no !== '') {
  $sql_data = "SELECT * FROM task_manage WHERE seri_no = '$seri_no'";
  $result_data = mysqli_query($conn, $sql_data);
  $dataExists = mysqli_num_rows($result_data) > 0;
?>
<div id="addTaskSection" class='bg-dark bg-opacity-10'> 
  <div class='row justify-content-center' style="max-width: 1900px; margin: 0 auto;">
    <section class="shadow-lg mt-1 p-1 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
      <div class='container-fluid' style='width: 1850px me-0 ms-0'>
        <form id="addTaskForm" action="task_process.php" method="POST">
          <input type="hidden" name="action" value="saveTask">
          <input type="hidden" id="seri_no" name="seri_no" value="<?= $seri_no ?>">
          <table class='table table-custom table-bordered mt-1' style="font-size: .65rem; width: 100%;">
            <thead style="max-width: 1850px; text-align: center;">
              <tr class='table table-secondary'>
                <th style="width: 3%;">No</th>
                <th style="width: 7%;">작업일자</th>
                <th style="width: 6%;">담당자</th>
                <th style="width: 5%;">구분</th>
                <th style="width: 7%;">항목</th>

                <th style="width: 18%;">작업내용</th>
                <th style="width: 28%;">세부내용</th>
                <th style="width: 16%;">특기사항</th>
                <th style="width: 6%;">가동상황</th>
                <th style="width: 4%;">
                  <button type="button" class="btn btn-success " style="font-size: .65rem" onclick="TB_BtnAdd()" title="작업 추가">+</button>
                </th>
              </tr>
            </thead>
            <tbody id="TB_Body" style="width: 1800px;">
              <?php      
              if (mysqli_num_rows($result_data) > 0) {
                  while ($row_data = mysqli_fetch_array($result_data)) {
                      $filtered = array(
                          'seri_no' => htmlspecialchars($row_data["seri_no"]),
                          't_no' => htmlspecialchars($row_data["t_no"]),
                          'date_task' => htmlspecialchars($row_data["date_task"]),
                          'task_person' => htmlspecialchars($row_data["task_person"]),
                          'task_title' => htmlspecialchars($row_data["task_title"]),
                          'task_content' => htmlspecialchars($row_data["task_content"]),
                          'task_aparts' => htmlspecialchars($row_data["task_aparts"]),
                          'hangmok' => htmlspecialchars($row_data["hangmok"]),
                          'specification' => htmlspecialchars($row_data["specification"]),
                          'manage_stat' => htmlspecialchars($row_data["manage_stat"]),
                      );
                      ?>
                <tr id="TB_Row" style="line-height: 30px !important;">    
                  <td><input type="text" class="t_no" style="text-align: center;" name="t_no[]" value="<?= $filtered['t_no']; ?>"></td>
                  <td><input type="text" class="form-control date_task" style="font-size: .65rem" name="date_task[]" value="<?= $filtered['date_task']; ?>"></td>
                  <td><input type="text" class="form-control task_person" style="font-size: .65rem" name="task_person[]" value="<?= $filtered['task_person']; ?>"></td>
                  <td><?= createSelectTask($conn, 'task_aparts[]', $filtered['task_aparts']); ?></td>                  
                  <td><?= updateSelectTaskPart($conn, 'hangmok[]', $filtered['hangmok']); ?></td>

                  <td><textarea class="form-control task_title" name="task_title[]" rows="1" style="font-size: .65rem; resize: none;"><?= $filtered["task_title"]; ?></textarea></td>
                  <td><textarea class="form-control task_content" name="task_content[]" rows="1" style="font-size: .65rem; resize: none;"><?= $filtered["task_content"]; ?></textarea></td>
                  <td><input type="text" class="form-control specification" style="font-size: .65rem" name="specification[]" value="<?= $filtered["specification"]; ?>"></td>
                  <td><?= createSelectStatus($conn, 'manage_stat[]', $filtered['manage_stat']); ?></td>
                  <td><button type="button" class="btn link-danger small-btn deleteButton" style="font-size: .65rem" title="작업 삭제" data-t_no="<?= $filtered['t_no']; ?>" data-seri_no="<?= $filtered['seri_no']; ?>"><i class="fa-solid fa-trash fs-6"></i></button></td>
                  <input type="hidden" class="seri_no" style="text-align: center;" name="seri_no" value="<?= $filtered['seri_no']; ?>">
                </tr>
                <?php
                  } // while 문 종료
              } else {
                  echo "<tr><td colspan='10' style='text-align: center;'> 작업정보를 추가 하려면 아래 작업추가 버튼을 클릭하십시오 " . mysqli_error($conn) . "</td></tr>";
              }
              ?>
            </tbody>
          </table>     
        <div class="footer">
            <div>
              <div>
                <?php if (!$dataExists): ?>             
                  <button type="button" class="btn btn-primary small-btn" style="font-size: .65rem" data-bs-toggle="modal" data-bs-target="#addTaskModal" data-seri_no="<?= $seri_no ?>">작업 추가</button>
                <?php endif; ?></div>
                <!-- <button type="button" class="btn btn-primary small-btn" style="font-size: .65rem" onclick="$('#addOptionModal').modal('show');">항목 추가</button> -->
                <button type="submit" class="btn btn-primary small-btn" style="font-size: .65rem">Save changes</button>
                <!-- <button type="button" class="btn btn-secondary small-btn" style="font-size: .65rem">Close</button> -->
            </div>
          </div>
        </div>
        </form>
      </div>
    </section>
  </div>
</div>
<?php 
} ?>
  <!-- Bootstrap JavaScript -->
  </div>
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="equipment.js"></script>
  <script src="facility.js"></script>
  <script src="task.js"></script>
  <script src="delete_task.js"></script>
            
</body>
<?php include('include/footer.php'); ?>
