<?php
$pageTitle = "AMESS 견적관리";
session_start();
include('include/header.php');
include(__DIR__ . '/../../db.php');

// 세션 메시지 확인 및 출력
if (isset($_SESSION['message'])) {
    $messageType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
    echo '<div class="alert alert-' . htmlspecialchars($messageType) . '" id="sessionMessage">' . htmlspecialchars($_SESSION['message']) . '</div>';
    unset($_SESSION['message']); // 메시지 출력 후 세션에서 제거
    unset($_SESSION['message_type']); // 메시지 타입 제거
}
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 성공 메시지를 일정 시간 후에 숨기기
    const sessionMessage = document.getElementById('sessionMessage');
    if (sessionMessage) {
        setTimeout(() => {
            sessionMessage.style.display = 'none';
        }, 5000); // 5초 후에 메시지 숨기기
    }
});
</script>

<!-- 견적관리 메인화면 -->
<div class='bg-success bg-opacity-10' style='text-align: center;'>
  <h4 class='bg-primary bg-opacity-10 mb-1 p-2' style='text-align: center'>견적관리</h4>
  <section class="shadow-lg mt-2 p-2 pt-0 my-4 rounded-3 container-fluid text-center justify-content-center ms-0" style='width:1920px'>
    <div class='container-fluid' style='width: 1920px; padding: 0 10px; display: flex; align-items: center; margin: 2px 2px;'>
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
      <!-- 신규 견적 등록 버튼 -->
      <div style="margin-left: auto;">
      <a class="btn btn-primary btn-sm" target="_blank" href="new_quote.php" style="--bs-btn-padding-y: .2rem; --bs-btn-padding-x: .4rem; --bs-btn-font-size: .65rem;">견적 등록</a>
      </div>
    </div>
    <div class='card-body'>
      <div id="searchResultContainer"></div>
      <table class="table table-striped table table-hover tatable-bordered mt-3 table-xl" style='font-size: .75rem'>
        <thead>
          <tr>
            <th style="width: 2%;">#</th>
            <th style="width: 3%;">부서</th>
            <th style="width: 6%;">견적번호</th>
            <th style="width: 6%;">견적일자</th>
            <th style="width: 6%;">고객명</th>

            <th style="width: 5%;">담당자</th>
            <th style="width: 5%;">작성자</th>
            <th style="width: 4%;">구분</th>
            <th style="width: 14%;">품명</th>
            <th style="width: 15%;">사양</th>

            <th style="width: 7%;">자재코드</th>
            <th style="width: 6%;">단가</th>
            <th style="width: 4%;">수량</th>
            <th style="width: 7%;">합계</th>
            <th style="width: 4%;">진행</th>
          </tr>
        </thead>
<tbody id="quoteTableBody" style="font-size: .75rem;">
  <?php
  $sql = "SELECT q.quote_no, q.quote_date, q.customer, q.customer_name, q.pic, q.picb, 
                qd.group_p, qd.sulbi, qd.model, qd.apart, qd.product_na, qd.product_sp, 
                qd.p_code, qd.price, qd.qty, qd.amt, qd.progress 
          FROM quote q 
          LEFT JOIN quote_data qd ON q.quote_no = qd.quote_no 
          ORDER BY q.quote_no DESC 
          LIMIT 30";
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $filtered = array(
      'picb' => htmlspecialchars($row['picb'] ?? ''),
      'quote_no' => htmlspecialchars($row['quote_no'] ?? ''),
      'quote_date' => htmlspecialchars($row['quote_date'] ?? ''),
      'customer' => htmlspecialchars($row['customer'] ?? ''),
      'customer_name' => htmlspecialchars($row['customer_name'] ?? ''),
      'pic' => htmlspecialchars($row['pic'] ?? ''),
      'group_p' => htmlspecialchars($row['group_p'] ?? ''),
      'sulbi' => htmlspecialchars($row['sulbi'] ?? ''),
      'model' => htmlspecialchars($row['model'] ?? ''),
      'apart' => htmlspecialchars($row['apart'] ?? ''),
      'product_na' => htmlspecialchars($row['product_na'] ?? ''),
      'product_sp' => htmlspecialchars($row['product_sp'] ?? ''),
      'p_code' => htmlspecialchars($row['p_code'] ?? ''),
      'price' => htmlspecialchars($row['price'] ?? 0),
      'qty' => htmlspecialchars($row['qty'] ?? 0),
      'amt' => htmlspecialchars($row['amt'] ?? 0),
      'progress' => htmlspecialchars($row['progress'] ?? '')
    );
  ?>
  <tr class="table table-hover">
    <td><input type="checkbox" class="row-checkbox" value="<?= $row['quote_no'] ?>"></td>
    <td><?= $filtered['picb'] ?></td>
    <td><?= $filtered['quote_no'] ?></td>
    <td><?= $filtered["quote_date"] ?></td>
    <td><?= $filtered['customer'] ?></td>
    <td><?= $filtered['customer_name'] ?></td>
    <td><?= $filtered['pic'] ?></td>
    <td><?= $filtered['apart'] ?></td>
    <td class="left-align"><?= $filtered['product_na'] ?></td>
    <td class="left-align"><?= $filtered['product_sp'] ?></td>
    <td><?= $filtered['p_code'] ?></td>
    <td class="right-align"><?= number_format($filtered['price']) ?></td>
    <td class="right-align"><?= number_format($filtered['qty']) ?></td>
    <td class="right-align"><?= number_format($filtered['amt']) ?></td>
    <td><?= $filtered['progress'] ?></td>
  </tr>
  <?php } ?>
</tbody>

      </table>
    </div>

  <!-- 편집, 삭제 버튼 -->
  <div style="position: fixed; bottom: 0; width: 100%; text-align: center; padding: 10px 0; background-color: transparent;">
    <button id="edit-button" onclick="redirectToEdit()" style="margin-right: 10px; background-color: #007bff; color: white; border: none; padding: 6px 12px; font-size: 14px;">편집</button>
    <button id="delete-button" onclick="deleteSelectedQuotes()" style="background-color: #dc3545; color: white; border: none; padding: 6px 12px; font-size: 14px;">삭제</button>
</div>
<script src="/practice/AMESystem/views/quote/js/quot.js" defer></script>

</body>
<?php
include('include/footer.php');
?>-