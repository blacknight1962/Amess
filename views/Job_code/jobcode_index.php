<?php
session_start();
$userName = isset($_SESSION['username']) ? $_SESSION['username'] : '';
include('include/header.php');
include('../public/Selection_kit.php');
include('../../db.php');
if (isset($_SESSION['status'])) {
  echo "<div class='alert alert-info' role='alert'>" . $_SESSION['status'] . "</div>";
  unset($_SESSION['status']); // 메시지를 한 번만 표시하고 세션에서 제거
}

?>
<!-- insert model -->
<div class="modal fade" id="insertdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="insertdata" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-6" id="insertdata">작업코드 등록</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action='process_jobcode.php' method="POST">
        <div class="modal-body" style="font-size: .65rem;">
          <div class='form-floating mb-1'>
            <input type='text' class='form-control' style="font-size: .65rem;" name='seri_no' placeholder="코드그룹">
            <label for="floatingInput">코드그룹</label>
          </div>
          <div class='form-floating mb-1'>
            <input type='text' class='form-control' style="font-size: .65rem;" name='equip' placeholder="장비명" required>
            <label for="floatingInput">장비명</label>
          </div>
          <div class='form-floating mb-1'>
            <input type='text' class='form-control' style="font-size: .65rem;" name='model_p' placeholder="모델명" required>
            <label for="floatingInput">모델명</label>
          </div>
          <div class='form-floating mb-1'>
            <input type='text' class='form-control' style="font-size: .65rem;" name='equip_ver' placeholder="버젼" required>
            <label for="floatingInput">버젼</label>
          </div>
          <div class='form-floating mb-1'>
            <input type='text' class='form-control' style="font-size: .65rem;" name='pic' placeholder="등록자"  value="<?= htmlspecialchars($userName) ?>"required>
            <label for="floatingInput">등록자</label>
          </div>
          <div class='form-group mb-1'>
            <label for="" name='reg_date'>등록일자</label>
            <?php echo date('Y-m-d') ?>
          </div>
          <div class='form-floating mb-1'>
            <input type='text' class='form-control' style="font-size: .65rem;" name='jobcode_specifi' placeholder="비고">
            <label for="floatingInput">비고</label>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" style="font-size: .65rem;" data-bs-dismiss="modal">Close</button>
            <button type="submit" name='save_data' class="btn btn-primary btn-sm" style="font-size: .65rem;">SAVE</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jobcode main screen -->
<div class='bg-success bg-opacity-10' style='text-align: center;'>
  <h4 class='bg-info bg-opacity-10 justify-content-center text-center mb-2 p-2'>작업코드 관리</h4>
  <section class="shadow-lg p-2 my-1 rounded-3 container-fluid text-center justify-content-center" style='width: 1440px; margin: 0 auto;'>
    <div class='container-fluid' style='width: 100%; padding: 0 10px; display: flex; flex-direction: column; align-items: center; margin: 2px 2px;'>
      <div style="display: flex; width: 100%; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <!-- 기간 선택 버튼 -->
        <div style="display: flex; align-items: center;">
          <button type="button" id="oneYearBtn" class="btn btn-outline-primary btn-sm me-2" style="font-size: .65rem; padding: .2rem .4rem;" onclick="setPeriod('1year')">최근 1년</button>
          <button type="button" id="threeYearsBtn" class="btn btn-outline-primary btn-sm me-2" style="font-size: .65rem; padding: .2rem .4rem;" onclick="setPeriod('3years')">최근 3년</button>
          <!-- 특정 년도 선택 드롭다운 -->
          <select id="yearSelect" class="form-select form-select-sm me-2" style="font-size: .65rem; width: auto;" onchange="handleYearChange(this.value)">
              <option value="">선택</option>
              <?php
              $currentYear = date("Y");
              for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
                  echo "<option value='$year'>$year</option>";
              }
              ?>
        </select>
        </div>
        <!-- 검색란 -->
        <input type='text' class='form-control form-control-sm me-2' style="font-size: .65rem; width: 30%;" name="searchQuery" id="getWords" autocomplete="off" placeholder="Search....">
        <!-- 숨겨진 입력 필드로 선택된 기간 저장 -->
        <input type="hidden" name="selectedPeriod" id="selectedPeriod">
        <div style="margin-left: auto; margin-right: 20px;">
          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary mt-1 mb-2 float-end" name="submit" style="--bs-btn-padding-y: .4rem; --bs-btn-padding-x: .15rem; --bs-btn-font-size: .65rem;" data-bs-toggle="modal" data-bs-target="#insertdata">
            신규장비 등록
          </button>
        </div>
      </div>
      <div class='card-body' style='width: 100%; margin-top: 0px !important;'>
      <div id="searchresult_j"></div>
        <table class="table table-hover table-striped table-bordered mt-3" style='font-size: .75rem; width: 100%; margin-top: 0px !important;'>
          <thead>
            <tr>
              <th scope="col">코드그룹</th>
              <th scope="col">장비명</th>
              <th scope="col">모델명</th>
              <th scope="col">버젼</th>
              <th scope="col">등록자</th>
              <th scope="col">등록일자</th>
              <th scope="col">비고</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT * FROM jobcode ORDER BY seri_no DESC";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)) {
              $filtered = array(
                'seri_no' => htmlspecialchars($row['seri_no']),
                'equip' => htmlspecialchars($row['equip']),
                'model_p' => htmlspecialchars($row['model_p']),
                'equip_ver' => htmlspecialchars($row['equip_ver']),
                'pic' => htmlspecialchars($row['pic']),
                'regi_date' => htmlspecialchars($row['regi_date']),
                'jobcode_specifi' => htmlspecialchars($row['jobcode_specifi']),
              );
            ?>
              <tr>
                <td><?= $filtered['seri_no'] ?></td>
                <td><?= $filtered['equip'] ?></td>
                <td><?= $filtered['model_p'] ?></td>
                <td><?= $filtered['equip_ver'] ?></td>
                <td><?= $filtered['pic'] ?></td>
                <td><?= $filtered['regi_date'] ?></td>
                <td><?= $filtered['jobcode_specifi'] ?></td>
                <td><a href="edit_jobcode.php?id='<?= $filtered['seri_no'] ?>'" class="link-primary" target="_blank"><i class="fa-solid fa-pen-to-square fs-6 me-3"></i></a>
                  <a href="javascript:void()" onClick="confirmter(<?php echo $row['seri_no'] ?>)" class=" link-secondary"><i class="fa-solid fa-trash fs-6"></i></a>
                </td>
              </tr>
            <?php } ?>
        </table>
      </div>
    </div>
  </section>
</div>
<script src="js/1search_jobcode.js"></script>
<?php include('include/footer.php'); ?>
