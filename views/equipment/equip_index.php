<?php
session_start();
$_SESSION['date'] = date('Y-m-d');

ini_set('display_errors', 1);
error_reporting(E_ALL);
include('include/header.php');
include('equip_modals.php');

if (isset($_SESSION['status'])) {
    $message = $_SESSION['status'];
    unset($_SESSION['status']); // 메시지 출력 후 세션에서 삭제
    echo "<script type='text/javascript'>alert('$message');</script>";
}
?>
<body>
<!-- 메인화면 -->
<div class='bg-success bg-opacity-10' style='text-align: center;'>
  <h4 class='bg-primary bg-opacity-10 mb-1 p-2' style='text-align: center'>장비관리</h4>
  <section class="shadow-lg mt-1 p-2 pt-0 my-4 rounded-3 container-custom text-center justify-content-center">
    <div class='container-fluid' style='padding: 0 10px; display: flex; align-items: center; margin: 2px 2px;'>
      <!-- 기간 선택 버튼 -->
      <button type="button" id="oneYearBtn" class="btn btn-outline-primary btn-sm me-2" style="font-size: .65rem; padding: .2rem .4rem;" onclick="setPeriod('1year')">최근 1년</button>
      <button type="button" id="threeYearsBtn" class="btn btn-outline-primary btn-sm me-2" style="font-size: .65rem; padding: .2rem .4rem;" onclick="setPeriod('3years')">최근 3년</button>
      <!-- 특정 년도 선택 드롭다운 -->
      <select id="yearSelect" class="form-select form-select-sm me-2" style="font-size: .65rem; width: auto;">
          <option value="">선택</option>
          <?php
          $currentYear = date("Y");
          for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
              echo "<option value='$year'>$year</option>";
          }
          ?>
      </select>
      <!-- 검색란 -->
      <input type='text' class='form-control form-control-sm me-2' style="font-size: .65rem; width: 30%;" name="searchQuery" id="searchInput" autocomplete="off" placeholder="Search....">
      <!-- 숨겨진 입력 필드로 선택된 기간 저장 -->
      <input type="hidden" name="selectedPeriod" id="selectedPeriod">
      <button type="button" class="btn btn-outline-primary btn-sm me-2" id="searchButton" style="font-size: .65rem; width: 6%; text-decoration: none; color:inherit;"><a href="task_search.php" target="_blank" style="text-decoration: none; color:inherit;">통합검색</a>
      </button>
      <!-- 신규 견적 등록 버튼 -->
      <div style="margin-left: auto;">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary mt-1 mb-2 float-end" name="submit" style="--bs-btn-padding-y: .4rem; --bs-btn-padding-x: .15rem; --bs-btn-font-size: .65rem;" data-bs-toggle="modal" data-bs-target="#insertdata">
          신규장비 등록
        </button>
      </div>
    </div>
    <div class='card-body'>
      <div id="searchResultContainer"></div>
      <table class="table table-striped table-bordered table-hover mt-1 table-xl table-custom" style='font-size: .65rem'>
        <thead>
          <tr>
            <th style="width: 4%;">no</th>
            <th style="width: 6%;">부서</th>
            <th style="width: 15%;">장비명</th>
            <th style="width: 16%;">모델명</th>
            <th style="width: 8%;">등록일자</th>

            <th style="width: 7%;">고객명</th>
            <th style="width: 8%;">공급사</th>
            <th style="width: 12%;">공정</th>
            <th style="width: 15%;">특기사항</th>
            <th style="width: 10%;">편집</th>
          </tr>
        </thead>
        <tbody id="equipmentTableBody" style="font-size: .65rem;">
          <?php
          $sql = "SELECT * FROM equipment ORDER BY e_no DESC";
          $result = mysqli_query($conn, $sql);
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
              $filtered = array(
                'e_no' => htmlspecialchars($row['e_no']),
                'picb' => htmlspecialchars($row['picb']),
                'equip' => htmlspecialchars($row['equip']),
                'model_p' => htmlspecialchars($row['model_p']),
                'regi_date' => htmlspecialchars($row['regi_date']),
                'customer' => htmlspecialchars($row['customer']),
                'supplyer' => htmlspecialchars($row['supplyer']),
                'process_p' => htmlspecialchars($row['process_p']),
                'specif' => htmlspecialchars($row['specif'])
              );
                  ?>
                      <tr class="table table-hover">
                        <td><?= $filtered['e_no'] ?></td>
                        <td><?= $filtered['picb'] ?></td>
                        <td><?= $filtered['equip'] ?></td>
                        <td><?= $filtered["model_p"] ?></td>
                        <td><?= $filtered["regi_date"] ?></td>
                        <td><?= $filtered['customer'] ?></td>
                        <td><?= $filtered['supplyer'] ?></td>
                        <td><?= $filtered["process_p"] ?></td>
                        <td><?= $filtered['specif'] ?></td>
                        <td style="padding: 1px 10px;">
                            <!-- Icon button trigger modal with e_no parameter -->
                            <a href="#" onclick="openPopupWindow(<?= $filtered['e_no'] ?>);" class="link-secondary" title="편집"><i class="fa-solid fa-pen-to-square fs-6"></i></a>
                            <!-- <button type="button" class="btn btn-link p-0 border-0 background-transparent" data-bs-toggle="modal" data-bs-target="#editEquipData" data-equipmentId="<?= $filtered['e_no']; ?>" title="편집" style="background: none; border: none;"> -->
                            <!-- <i class="fa-solid fa-pen-to-square fs-6"></i> -->
                            </a>
                            <button type="button" class="btn btn-link delete-button btn-minimal-padding btn-sm" data-equip-id="<?= $filtered['e_no']; ?>" title="삭제">
                                <i class="fa-solid fa-trash fs-6" style="padding: 0px 2px;"></i>
                            </button>  
                            <a href="facility_index.php?id=<?= $filtered['e_no'] ?>" class="link-secondary" title="상세정보">
                                <i class="fa-solid fa-arrow-right fs-6"></i>
                            </a>
                        </td>
                      </tr>
                    <?php
                    }
                  } else {
                    ?>
                    <td colspan="4">No More Data</td>
                  <?php
                  } ?>

                </tbody>
              </table>
            </div>
        </section>
      </div>
    </div>
  </div>
</div>
  <script src="js/equipment.js"></script>
  <script src='js/task.js'></script>
  <!-- <script src="equip.js"></script> -->
</body>
<?php include('include/footer.php'); ?>