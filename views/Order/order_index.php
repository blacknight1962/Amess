<?php
$pageTitle = "AMESS 발주관리";
include('include/header.php');
include(__DIR__ . '/../../db.php');

// 세션 메시지 확인 및 출력
if (isset($_SESSION['message'])) {
    if (is_array($_SESSION['message'])) {
        foreach ($_SESSION['message'] as $message) {
            echo '<div class="alert alert-info" id="sessionMessage">' . htmlspecialchars($message) . '</div>';
        }
    } else {
        echo '<div class="alert alert-info" id="sessionMessage">' . htmlspecialchars($_SESSION['message']) . '</div>';
    }
    unset($_SESSION['message']); // 메시지 출력 후 세션에서 제거
}

function fetchOrderInfo($order_no) {
    global $conn; // 데이터베이스 연결 객체를 전역에서 가져옴

    // 쿼리에서 변수를 직접 삽입하는 대신 플레이스홀더 사용
    $query = "SELECT o.*, d.* FROM order_basic o LEFT JOIN order_data d ON o.order_no = d.order_no WHERE o.order_no = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('쿼리 준비 실패: ' . $conn->error);
    }
    $stmt->bind_param("s", $order_no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status === 'saved') {
        alert('저장되었습니다.');
        const saveButton = document.getElementById('saveButton');
        if (saveButton) {
            saveButton.textContent = '저장됨';
        }
    }

    // 성공 메시지를 일정 시간 후에 숨기기
    const sessionMessage = document.getElementById('sessionMessage');
    if (sessionMessage) {
        setTimeout(() => {
            sessionMessage.style.display = 'none';
        }, 5000); // 5초 후에 메시지 숨기기
    }
});
</script>
<!--저장실패시 입력하던 데이터 유지-->
<?php
if (isset($_GET['data'])) {
    $data = unserialize(urldecode($_GET['data']));
    // 이제 $data 배열을 사용하여 폼 필드를 채울 수 있습니다.
}
?>
<body>
<!-- 발주번호 DB조회 -->
<?php
if (isset($_GET['id'])) {
    $order_no = $_GET['id'];
    $order_info = fetchOrderInfo($order_no);
}
?>
<!-- 발주관리 main screen -->
<div class='bg-success bg-opacity-10' style='text-align: center;'>
  <h4 class='bg-primary bg-opacity-10 mb-1 p-2' style='text-align: center'>영업관리 - 발주관리</h4>
  <section class="shadow-lg mt-1 p-2 pt-0 my-4 rounded-3 container-fluid text-center justify-content-center ms-0">
    <div class='container-fluid' style='padding: 0 10px; display: flex; align-items: center; margin: 2px 2px;'>
      <!-- 기간 선택 버튼 -->
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
      <!-- 검색란 -->
      <input type='text' class='form-control form-control-sm me-2' style="font-size: .65rem; width: 30%;" name="searchQuery" id="getWords" autocomplete="off" placeholder="Search....">
      <!-- 숨겨진 입력 필드로 선택된 기간 저장 -->
      <input type="hidden" name="selectedPeriod" id="selectedPeriod">
      <!-- 신규 발주 등록 버튼 -->
      <div style="margin-left: auto; margin-right: 20px;">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary mt-1 mb-0 float-end" name="submit" style="--bs-btn-padding-y: .4rem; --bs-btn-padding-x: .15rem; --bs-btn-font-size: .65rem;">
          <a href="order_new.php" target="_blank" style="color: white; text-decoration: none;">발주 등록</a>
        </button>
      </div>
    </div>
    <div class='card-body'>
        <div id="searchResultContainer_o"></div>
        <table class="table table-striped table-bordered table-hover mt-2 table-xl" style='font-size: .75rem'>
          <thead style="text-align: center;">
            <tr>
              <th style="width: 3%;">#</th>
              <th style="width: 3%;">번호</th>
              <th style="width: 3%;">부서</th>
              <th style="width: 5%;">발주일자</th>
              <th style="width: 6%;">발주번호</th>
              <th style="width: 5%;">발주사</th>

              <th style="width: 5%;">고객사</th>
              <th style="width: 5%;">특기</th>
              <th style="width: 4%;">구분</th>
              <th style="width: 5%;">담당자</th>
              <th style="width: 7%;">자재코드</th>

              <th style="width: 7%;">품명</th>
              <th style="width: 8%;">사양</th>
              <th style="width: 5%;">요청납기</th>
              <th style="width: 5%;">단가</th>
              <th style="width: 3%;">단위</th>

              <th style="width: 3%;">수량</th>
              <th style="width: 6%;">합 계</th>
              <th style="width: 3%;">환율</th>
              <th style="width: 5%;">매출일자</th>
              <th style="width: 4%;">진행</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $sql = "SELECT o.order_no, od.picb, od.o_no, o.order_date, o.order_custo, o.customer, od.specifi, 
                        od.aparts, o.custo_name, od.parts_code, od.product_na, od.product_sp, 
                        od.requi_date, od.price, od.currency, od.qty, od.amt, od.curency_rate,od.sales_date, od.condit 
                  FROM `order` o 
                  LEFT JOIN `order_data` od ON o.order_no = od.order_no 
                  ORDER BY o.order_date DESC, od.o_no ASC";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_array($result)) {
            $filtered = array(
              'order_no' => htmlspecialchars($row['order_no'] ?? ''),
              'picb' => htmlspecialchars($row['picb'] ?? ''),
              'o_no' => htmlspecialchars($row['o_no'] ?? ''),
              'order_date' => htmlspecialchars($row['order_date'] ?? ''),
              'order_custo' => htmlspecialchars($row['order_custo'] ?? ''),

              'customer' => htmlspecialchars($row['customer'] ?? ''),
              'specifi' => htmlspecialchars($row['specifi'] ?? ''),
              'aparts' => htmlspecialchars($row['aparts'] ?? ''),
              'custo_name' => htmlspecialchars($row['custo_name'] ?? ''),
              'parts_code' => htmlspecialchars($row['parts_code'] ?? ''),

              'product_na' => htmlspecialchars($row['product_na'] ?? ''),
              'product_sp' => htmlspecialchars($row['product_sp'] ?? ''),
              'requi_date' => htmlspecialchars($row['requi_date'] ?? ''),
              'price' => htmlspecialchars($row['price'] ?? 0),
              'currency' => htmlspecialchars($row['currency'] ?? ''),
              
              'qty' => htmlspecialchars($row['qty'] ?? 0),
              'amt' => htmlspecialchars($row['amt'] ?? 0),
              'curency_rate' => htmlspecialchars($row['curency_rate'] ?? ''),
              'sales_date' => htmlspecialchars($row['sales_date'] ?? ''),
              'condit' => htmlspecialchars($row['condit'] ?? '')
            );
          ?>
          <tbody id="order-table-body">
            <tr>
              <td class="center-checkbox"><input type="checkbox" class="row-checkbox" value="<?= $row['order_no'] ?>"></td>
              <td style="text-align: center;"><?= $filtered['o_no'] ?></td>
              <td style="text-align: center;"><?= $filtered['picb'] ?></td>
              <td style="text-align: center;"><?= $filtered['order_date'] ?></td>
              <td style="text-align: center;"><?= $filtered['order_no'] ?></td>
              <td style="text-align: center;"><?= $filtered['order_custo'] ?></td>
              <td style="text-align: center;"><?= $filtered['customer'] ?></td>

              <td style="text-align: center;"><?= $filtered['specifi'] ?></td>
              <td style="text-align: center;"><?= $filtered['aparts'] ?></td>
              <td style="text-align: center;"><?= $filtered['custo_name'] ?></td>
              <td style="text-align: center;"><?= $filtered['parts_code'] ?></td>

              <td style="text-align: left;"><?= $filtered['product_na'] ?></td>
              <td style="text-align: left;"><?= $filtered['product_sp'] ?></td>
              <td style="text-align: center;"><?= $filtered['requi_date'] ?></td>
              <td style="text-align: right;"><?= number_format((float)$filtered['price']) ?></td>
              <td style="text-align: center;"><?= $filtered['currency'] ?></td>

              <td style="text-align: center;"><?= $filtered['qty'] ?></td>
              <td style="text-align: right;"><?= number_format((float)$filtered['amt']) ?></td>
              <td style="text-align: center;"><?= $filtered['curency_rate'] ?></td>
              <td style="text-align: center;"><?= $filtered['sales_date'] ?></td>
              <td style="text-align: center;"><?= $filtered['condit'] ?></td>              
            </tr>
          <?php
            } ?>
          </tbody>
        </table>
      </div>
  </section>
</div>
</div>
</div>
  <!-- 편집, 삭제 버튼 -->
  <div style="position: fixed; bottom: 0; width: 100%; text-align: center; padding: 10px 0; background-color: transparent;">
    <button id="edit-button" style="margin-right: 10px; background-color: #007bff; color: white; border: none; padding: 6px 12px; font-size: 14px;">편집</button>
    <button id="delete-button" onclick="deleteSelectedQuotes()" style="background-color: #dc3545; color: white; border: none; padding: 6px 12px; font-size: 14px;">삭제</button>
</div>
<script src="js/order.js"></script>
<script src="js/order_search.js"></script>
</body>
<?php include('include/footer.php'); ?>
