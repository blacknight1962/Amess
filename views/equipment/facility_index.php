<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('include/header.php');
include('../../db.php'); // 데이터베이스 연결 확인

if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}

$dataExist = false; // 초기화

// $e_no 변수 초기화
if (isset($_GET['id'])) {
    $e_no = mysqli_real_escape_string($conn, $_GET['id']);
} elseif (isset($_SESSION['e_no'])) {
    $e_no = mysqli_real_escape_string($conn, $_SESSION['e_no']);
} else {
    $e_no = 'undefined';
}

// $dataExist 변수 초기화
if ($e_no !== 'undefined') {
    $sql_data = "SELECT * FROM facility WHERE e_no = '$e_no'";
    $result_data = mysqli_query($conn, $sql_data);
    if (!$result_data) {
        die('쿼리 오류: ' . mysqli_error($conn)); // 쿼리 오류 확인
    }
    $dataExist = mysqli_num_rows($result_data) > 0;
}
?>
<body>
  <!--신규 설비 등록 Modal -->
<div class="modal fade custom-modal-size" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fs-6" id="staticBackdropLabel">설비 추가</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
          <section class="shadow-lg mt-1 p-1 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
            <div class='container-fluid' style='width: 1550px me-0 ms-0'>
              <form action="facility_process.php" method="post">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="e_no" value="<?php echo $e_no; ?>">
              <table class='table table-bordered mt-1' style="font-size: .65rem; width: 100%;">
                <thead style="max-width: 1500px; text-align: center;">
                  <tr class='table table-secondary'>
                    <th style="width: 6%;">No</th>
                    <th style="width: 10%;">S/N</th>
                    <th style="width: 10%;">납품일자</th>
                    <th style="width: 10%;">사업장</th>
                    <th style="width: 7%;">설치라인</th>
                    <th style="width: 12%;">고객명칭</th>
                    <th style="width: 14%;">S/W ver.</th>
                    <th style="width: 10%;">가동상황</th>
                    <th style="width: 16%;">특기사항</th>
                    <th style="width: 5%;"><button type="button" id="addbutton" class="btn btn-success" style="font-size: .65rem; padding: 2px 0px;" onclick="F_BtnAdd()">+</button></th>
                </tr>
              </thead>
              <tbody id='T_Body' style="width: 1500px; font-size: .65rem;">
                <tr id='T_Row'>
                  <td><input type="text" class="form-control sub_no" style="text-align: center; width: 100%;" name="sub_no[]" value="<?php echo isset($row["sub_no"]) ? $row["sub_no"] : "1"; ?>"></td>
                  <td><input type="text" class="form-control seri_no" style="font-size: .65rem; width: 100%;" name="seri_no[]" value="<?php echo isset($row["seri_no"]) ? $row["seri_no"] : ""; ?>"></td>
                  <td><input type="date" class="form-control date_supply" style="font-size: .65rem; width: 100%;" name="date_supply[]" value="<?php echo isset($row["date_supply"]) ? $row["date_supply"] : ""; ?>"></td>
                  <td><input type="text" class="form-control place_fac" style="font-size: .65rem; width: 100%;" name="place_fac[]" value="<?php echo isset($row["place_fac"]) ? $row["place_fac"] : ""; ?>"></td>
                  <td><input type="text" class="form-control line_no" style="font-size: .65rem; width: 100%;" name="line_no[]" value="<?php echo isset($row["line_no"]) ? $row["line_no"] : ""; ?>"></td>
                  <td><input type="text" class="form-control custo_nick" style="font-size: .65rem; width: 100%;" name="custo_nick[]" value="<?php echo isset($row["custo_nick"]) ? $row["custo_nick"] : ""; ?>"></td>
                  <td><input type="text" class="form-control sw_ver" style="font-size: .65rem; width: 100%;" name="sw_ver[]" value="<?php echo isset($row["sw_ver"]) ? $row["sw_ver"] : ""; ?>"></td>
                  <td><select class="form-select" name="manage_stat[]" aria-label="" style="font-size: .65rem; width: 100%;" value="<?php echo isset($row['manage_stat']) ? $row['manage_stat'] : ''; ?>">
                      <option selected>선택</option>
                      <option value="정상작동">정상가동</option>
                      <option value="수리진행">수리진행</option>
                      <option value="작업대기" selected>작업대기</option>
                      <option value="장비점검">장비점검</option>
        <option value="개조진행">개조진행</option>
        <option value="기타사항">기타사항</option>
      </select></td>
    <td><input type="text" class="form-control specif" style="font-size: .65rem; width: 100%;" name="specif[]" value="<?php echo isset($row["specif"]) ? $row["specif"] : ""; ?>"></td>
    <td>
      <button type="button" class="btn link-danger small-btn" style="font-size: .65rem" onclick="BtnDelM(this)">
        <i class="fa-solid fa-trash fs-6"></i></button>
    </td>
  </tr>
</tbody>
</table>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary btn-sm" style="font-size: .65rem;" data-bs-dismiss="modal">Close</button>
  <button type="submit" class="btn btn-primary btn-sm" style="font-size: .65rem;">Save changes</button>
</div>
</form>
</div>
</section>
</div>
</div>
</div>
</div>

<!-- 설비관리 메인화면 -->
<div class='bg-danger bg-opacity-10' style='text-align: center;'>
  <h4 class='bg-secondary bg-opacity-10 mb-1 p-2' style='text-align: center'>장비 - 설비관리</h4>
  <section class="shadow-lg mt-2 p-2 pt-0 my-4 rounded-3 container-custom text-center justify-content-center">
    <div class='container-fluid' style='padding: 0 10px; display: flex; align-items: center; margin: 2px 2px;'>
      <!-- 검색란 -->
      <input type='text' class='form-control form-control-sm me-2' style="font-size: .65rem; width: 30%;" name="searchQuery" id="searchInput" autocomplete="off" placeholder="Search....">
      <button type="button" class="btn btn-outline-primary btn-sm me-2" id="searchButton" style="font-size: .65rem; width: 6%; text-decoration: none; color:inherit;"><a href="task_search.php" target="_blank" style="text-decoration: none; color:inherit;">통합검색</a>
      </button>
    </div>
    <!-- 장비관리- 설비 추가 -->
    <div class='card-body'>
      <table class="table table-striped table-bordered table-hover mt-1 table-xl" style='font-size: .65rem'>
        <thead>
          <tr>
            <th style="width: 4%;">no</th>
            <th style="width: 4%;">부서</th>
            <th style="width: 15%;">장비명</th>
            <th style="width: 16%;">모델명</th>
            <th style="width: 8%;">등록일자</th>
            <th style="width: 7%;">고객명</th>
            <th style="width: 8%;">공급사</th>
            <th style="width: 11%;">공정</th>
            <th style="width: 17%;">특기사항</th>
            <th style="width: 9%;">편집</th>
          </tr>
        </thead>
        <tbody class='' style="font-size: .65rem;">
          <?php
          $sql = "SELECT * FROM equipment WHERE e_no = '$e_no'";
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
                      <tr class="table table-hover" style="line-height: 20px !important;">
                        <td><?= $filtered['e_no'] ?></td>
                        <td><?= $filtered['picb'] ?></td>
                        <td><?= $filtered['equip'] ?></td>
                        <td><?= $filtered["model_p"] ?></td>
                        <td><?= $filtered["regi_date"] ?></td>
                        <td><?= $filtered['customer'] ?></td>
                        <td><?= $filtered['supplyer'] ?></td>
                        <td><?= $filtered["process_p"] ?></td>
                        <td><?= $filtered['specif'] ?></td>
                        <td>
                            <!-- Icon button trigger modal with e_no parameter -->
                            <a href="#" onclick="openPopupWindow(<?= $filtered['e_no'] ?>);" class="link-secondary" title="편집"><i class="fa-solid fa-pen-to-square fs-6" style="padding: 0px 2px;"></i></a>
                            <button type="button" class="btn btn-link delete-button btn-minimal-padding" data-equip-id="<?= $filtered['e_no']; ?>" title="삭제">
                                <i class="fa-solid fa-trash fs-6" style="padding: 0px 2px;"></i>
                            </button>  
                        </td>
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
    </div>
  </section>
</div>
</div class=>
<!-- 설비 조회 및 update -->
<div class='bg-warning bg-opacity-10'>
  <div class='row justify-content-center' style="max-width: 1550px; margin: 0 auto;">
    <section class="shadow-lg mt-1 p-1 pt-0 my-4 rounded-3 container-fluid justify-content-center text-center ms-0">
      <div class='container-fluid' style='width: 1550px me-0 ms-0'>
        <form action="facility_process.php" method="post">
          <input type="hidden" name="e_no" value="<?php echo $e_no; ?>">
          <input type="hidden" name="action" value="save">
          <?php
          if ($e_no !== 'undefined') {
          $sql_data = "SELECT * FROM facility ORDER BY e_no DESC LIMIT 30";
          $result_data = mysqli_query($conn, $sql_data);
          if (!$result_data) {
              die('쿼리 오류: ' . mysqli_error($conn)); // 쿼리 오류 확인
          }
          $dataExist = mysqli_num_rows($result_data) > 0;
            }
          ?>
          <div class="float-end">
            <!-- Button trigger modal -->
        <?php if (!$dataExist): ?>
          <button type="button" class="btn btn-primary mt-1 mb-2 mx-auto d-block" name="submit" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="--bs-btn-padding-y: .4rem; --bs-btn-padding-x: .15rem; --bs-btn-font-size: .65rem;">
                          설비추가
          </button>

        <?php endif; ?>
      </div>
      <table class='table table-bordered mt-1' style="font-size: .65rem; width: 100%;">
        <thead style="max-width: 1500px; text-align: center; vertical-align: middle;">
          <tr class='table table-secondary'>
            <th style="width: 4%;">No</th>
            <th style="width: 13%;">S/N</th>
            <th style="width: 10%;">납품일자</th>
            <th style="width: 10%;">사업장</th>
            <th style="width: 7%;">설치장소</th>
            <th style="width: 11%;">고객명칭</th>
            <th style="width: 15%;">S/W ver.</th>
            <th style="width: 9%;">가동상황</th>
            <th style="width: 17%;">특기사항</th>
            <th style="width: 5%;"><button type="button" id="addBtton" class="btn btn-success btn-sm" style="font-size: .65rem" onclick="FT_BtnAdd()">+</button></th>
          </tr>
        </thead>
        <tbody id='FT_Body' style="width: 1500px; font-size: .65rem;">
          <?php
          // $e_no 변수가 설정되어 있는지 확인하고, 없으면 기본값으로 처리
            $sql_data = "SELECT * FROM facility ORDER BY e_no DESC LIMIT 30";
            $result_data = mysqli_query($conn, $sql_data);
            
            if (mysqli_num_rows($result_data) > 0) {
              while ($row_data = mysqli_fetch_array($result_data)) {
                  $filtered = array(
              'e_no' => htmlspecialchars($row_data["e_no"]),
              'sub_no' => htmlspecialchars($row_data["sub_no"]),
              'seri_no' => htmlspecialchars($row_data["seri_no"]),
              'date_supply' => htmlspecialchars($row_data["date_supply"]),
              'place_fac' => htmlspecialchars($row_data["place_fac"]),
              'line_no' => htmlspecialchars($row_data["line_no"]),
              'custo_nick' => htmlspecialchars($row_data["custo_nick"]),
              'sw_ver' => htmlspecialchars($row_data["sw_ver"]),
              'manage_stat' => htmlspecialchars($row_data["manage_stat"]),
              'specif' => htmlspecialchars($row_data["specif"]),
              ); //array 출력 
        ?>
          <tr id='FT_Row' data-e-no="<?php echo $filtered['e_no']; ?>"> <!-- e_no 값을 data 속성으로 추가 -->
            <td><input type="text" class="form-control sub_no" style="text-align: center;" name="sub_no[]" value="<?php echo $filtered["sub_no"]; ?>"></td>
            <td><input type="text" class="form-control seri_no"  name="seri_no[]" value="<?php echo $filtered["seri_no"]; ?>"></td>
            <td><input type="text" class="form-control date_supply" name="date_supply[]" value="<?php echo $filtered["date_supply"]; ?>"></td>
            <td><input type="text" class="form-control place_fac" name="place_fac[]" value="<?php echo $filtered["place_fac"]; ?>"></td>
            <td><input type="text" class="form-control line_no" name="line_no[]" value="<?php echo $filtered["line_no"]; ?>"></td>
            <td><input type="text" class="form-control custo_nick" name="custo_nick[]" value="<?php echo $filtered["custo_nick"]; ?>"></td>
            <td><input type="text" class="form-control sw_ver" name="sw_ver[]" value="<?php echo $filtered["sw_ver"]; ?>"></td>
            <td><?= createSelectStatus($conn, 'manage_stat[]', $filtered['manage_stat']); ?></td>
            <td><input type="text" class="form-control specif" name="specif[]" value="<?php echo $filtered["specif"]; ?>"></td>
            <td><button type="button" class="btn link-danger small-btn btn-delete-db BtnDelF" style="font-size: .65rem">
                <i class="fa-solid fa-trash fs-6"></i></button>
                <a href="task_index.php?e_no=<?= $filtered['e_no'] ?>&sub_no=<?= $filtered['sub_no'] ?>&seri_no=<?= $filtered['seri_no'] ?>"" class="link-secondary" title="상세정보">
                <i class="fa-solid fa-arrow-right fs-6"></i></a>
            </td>
          </tr> 
  <?php    } //while 문 종료 
            } else 
              echo "<tr><td colspan='11' style='text-align: center;'>현재 데이터가 없습니다. 추가하려면 <span style='color: blue;'>설비추가</span>을 클릭해주세요.</td></tr>";
            ?> 
          </tbody>
        </table>
        <div class="row">
          <div class="col-1">
            <button type="submit" class="btn btn-success" style="font-size: .65rem">UPDATE</button>
          </div>
        </div>
    </div>
  </section>
  </div>
  </div>
  <!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/equipment.js"></script>
<script src="js/facility.js"></script>
</body>
<?php include('include/footer.php'); ?>

