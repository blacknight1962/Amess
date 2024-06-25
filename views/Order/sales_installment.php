<?php
$pageTitle = "매출관리-매출등록";
include('include/header.php');
include('../../db.php');
// header('Content-Type: application/json');


function fetchOrderInfo($conn, $order_no) {
    $query = "SELECT * FROM `order` WHERE order_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $order_no);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

$order_info = array();
$order_no = ""; // 초기 order_no를 빈 문자열로 설정

if (isset($_GET['order_no'])) {
    $order_no = $_GET['order_no'];
    $order_info = fetchOrderInfo($conn, $order_no);
}
?>
  <!-- 발주 기본정보 입력 -->
<div class='bg-info bg-opacity-10 mt-1' style="width: 1920px; margin: 0 auto;">
  <div class='container mt-1'>
    <div class='row justify-content-center'>
      <div class='bg-warning bg-opacity-10'>
        <h4 class='bg-primary bg-opacity-10 mb-1 p-2' style='text-align: center'>매출등록</h4>
          <section class="shadow-lg p-0 my-1 rounded-3 container text-center">
            <div class='container-fluid' style='width: 1920px me-1 ms-1'>
              <form action="order_process.php" method='post'>
                <input type="hidden" name="action_type" value="save_basic">
                <table class='table table-bordered mt-1' style="font-size: .65rem; width: 1820px;">
                  <thead style="max-width: 1820px; text-align: center;">
                    <tr class='table table-warning' style="margin: 0 auto;">
                      <th style="width: 10%;">발주번호</th>
                      <th style="width: 10%;">발주사</th>
                      <th style="width: 13%;">고객사</th>
                      <th style="width: 10%;">발주일자</th>
                      <th style="width: 10%;">담당자</th>

                      <th style="width: 12%;">특기사항</th>
                      <th style="width: 13%;">생산코드</th>
                      <th style="width: 12%;">착수일자</th>
                      <th style="width: 10%;"></th>
                    </tr>
                  </thead>
                  <tbody style="text-align: center !important;">
                    <tr>
                      <td><input type='text' class='form-control' style='border:none' placeholder="발주번호" name='order_no' value="<?php echo isset($order_info['order_no']) ? $order_info['order_no'] : ''; ?>" required></td>
                      <td><?= createSelectOrderCustomer($conn, isset($order_info['order_custo']) ? $order_info['order_custo'] : ''); ?></td>
                      <td><?= createSelectCustomer($conn, isset($order_info['customer']) ? $order_info['customer'] : ''); ?></td>
                      <td><input type='date' class='form-control' name='order_date' value="<?php echo isset($order_info['order_date']) ? $order_info['order_date'] : date('Y-m-d'); ?>" required></td>
                      <td><input type='text' class='form-control' placeholder='담당자' name='custo_name' value="<?php echo isset($order_info['custo_name']) ? $order_info['custo_name'] : ''; ?>"></td>
                      <td><input type='text' class='form-control' placeholder='특기사항' name='specifi' value="<?php echo isset($order_info['specifi']) ? $order_info['specifi'] : ''; ?>"></td>
                      <td><input type='text' class='form-control' placeholder='생산코드' name='production_code' value="<?php echo isset($order_info['production_code']) ? $order_info['production_code'] : ''; ?>"></td>
                      <td><input type='date' class='form-control' placeholder='착수일자' name='production_start' value="<?php echo isset($order_info['production_start']) ? $order_info['production_start'] : ''; ?>"></td>
                      <td></td>
                      </tr>
                  </tbody>
                </table>
              </form>
            </div>
          </section>
      </div>
    </div>
  </div>
</div>   
<?php
function fetchOrderDetails($conn, $order_no) {
    $query = "SELECT * FROM order_data WHERE order_no = ? ORDER BY o_no ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $order_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = [];
    while ($row = $result->fetch_assoc()) {
        $details[] = $row;
    }
    return $details;
}
?>
<!-- 발주 상세정보 -->
<div class='bg-info bg-opacity-10' style="width: 1920px; margin: 0 auto;">
  <div class='row justify-content-center' style="max-width: 1920px; margin: 0 auto;">
  <section class="shadow-lg mt-0 p-2 pt-0 my-3 rounded-3 container-fluid justify-content-center text-center ms-0">
    <div class='container-fluid' style='width: 1890px; padding-left: 0;'>
      <form action="order_process.php" method='post'>
        <input type='hidden' name='action_type' value='save_detail'>
        <input type='hidden' name='order_no' value='<?php echo $order_no;ENT_QUOTES ?>'>
          <table table class='table table-bordered mt-1' style="font-size: .65rem; width: 1900px;">
            <thead style="text-align: center;">
              <tr class='table table-warning'>
                <th style="width: 3%;">No</th>
                <th style="width: 4%;">부서</th>
                <th style="width: 5%;">구분</th>
                <th style="width: 9%;">자재코드</th>
                <th style="width: 14%;">품명</th>

                <th style="width: 14%;">사양</th>
                <th style="width: 8%;">요청납기</th>
                <th style="width: 7%;">단 가</th>
                <th style="width: 4%;">단위</th>
                <th style="width: 4%;">수량</th>

                <th style="width: 8%;">합계(원화)</th>
                <th style="width: 4%;">환율</th>
                <th style="width: 8%;">매출일자</th>
                <th style="width: 8%;">조건(%)</th>
              </tr>
            </thead>
            <tbody id="orderItemBody">
              <?php
              $order_data = fetchOrderDetails($conn, $order_no);
              // print_r($order_data);
              if (count($order_data) > 0) {
                  foreach ($order_data as $detail) { ?>
                    <tr id='orderItemRow' class='custom-tr'>
                      <td><?php echo isset($detail['o_no']) ? htmlspecialchars($detail['o_no']) : ''; ?></td>
                      <td><?php echo isset($detail['picb']) ? htmlspecialchars($detail['picb']) : ''; ?></td>
                      <td><?php echo isset($detail['aparts']) ? htmlspecialchars($detail['aparts']) : ''; ?></td>
                      <td><?php echo isset($detail['parts_code']) ? htmlspecialchars($detail['parts_code']) : ''; ?></td>
                      <td><?php echo isset($detail['product_na']) ? htmlspecialchars($detail['product_na']) : ''; ?></td>
                      <td><?php echo isset($detail['product_sp']) ? htmlspecialchars($detail['product_sp']) : ''; ?></td>
                      <td><?php echo isset($detail['requi_date']) ? htmlspecialchars($detail['requi_date']) : ''; ?></td>
                      <td><?php echo isset($detail['price']) ? htmlspecialchars(number_format($detail['price'])) : ''; ?></td>
                      <td><?php echo isset($detail['currency']) ? htmlspecialchars($detail['currency']) : ''; ?></td>
                      <td><?php echo isset($detail['qty']) ? htmlspecialchars($detail['qty']) : ''; ?></td>
                      <td><input type='text' class='form-control amt small-input right-align' style='border:none' name='amt[]' value="<?php echo isset($detail['amt']) ? htmlspecialchars(number_format($detail['amt'])) : ''; ?>"></td>
                      <td><?php echo isset($detail['curency_rate']) ? htmlspecialchars($detail['curency_rate']) : ''; ?></td>
                      <td><input type='date' class='form-control' style='border:none' name='sales_date[]' value="<?php echo isset($detail['sales_date']) ? htmlspecialchars($detail['sales_date']) : ''; ?>"></td>
                      <td><select class="form-select" name='condit[]' aria-label="" style="font-size: .65rem">
                          <option value="선택" <?php echo (!isset($detail['condit']) || $detail['condit'] == '선택') ? 'selected' : ''; ?>>선택</option>
                          <option value="일시불" <?php echo (isset($detail['condit']) && $detail['condit'] == '일시불') ? 'selected' : ''; ?>>일시불</option>
                          <option value="분할" <?php echo (isset($detail['condit']) && $detail['condit'] == '분할') ? 'selected' : ''; ?>>분할</option>
                          <option value="완료" <?php echo (isset($detail['condit']) && $detail['condit'] == '완료') ? 'selected' : ''; ?>>완료</option>
                      </select></td>                      
                    </tr>
                    <?php
                  }
              }
              ?>
            </tbody>
              <tr>
                <td colspan="14" class="text-right">NonePO 매출합계: <span id="totalSalesAmount">금액</span></td>
              </tr>
        </table>
        <div class='row'>
          <div class='col-1'>            
          </div>
          <div class='col-6'></div>
          <div class='col-3'>
            <div class='input-group mb-1'>
              <span class='input-group-text' style="font-size: .65rem;">발주 합계</span>
              <input type='text' class='form-control text-end large-bold-text' id='FTotal' name='FTotal' disabled=''/>
            </div>
          </div>
          <div class='col-2'>     
          </div>
        </div>
      </div>
    </form>
    </div>
  </section>
  </div>
</div>
<!-- 일시불 매출등록 -->
<?php
$order_no = $_GET['order_no'];
$query = "SELECT o_no FROM order_data WHERE order_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $order_no);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $o_no = $row['o_no'];
} else {
    $o_no = "Not found";
} ?>
<div class='bg-info bg-opacity-10 mt-1' style="width: 1920px; margin: 0 auto;">
  <div class='container mt-1'>
    <div class='row justify-content-center'>
      <div class='bg-warning bg-opacity-10' style="width: 1200px; margin: 0 auto;">
          <section class="shadow-lg p-0 my-1 rounded-3 container text-center">
            <div class='container-fluid' style='width: 1200px me-1 ms-1'>
              <form id='paymentForm' action="sales_process.php" method='post'>
                <input type="hidden" name="action_type" value="save_sales">
                <input type="hidden" name="order_no" value="<?php echo $order_no; ?>">
                <input type="hidden" name="o_no[]" value="<?php echo $o_no; ?>">
                <table class='table table-bordered mt-1' style="font-size: .75rem; width: 1100px;">
                  <thead style="max-width: 1000px; text-align: center;">
                    <tr class='table table-danger' style="margin: 0 auto;">
                      <th>일시불 매출</th>
                      <th>발주번호</th>
                      <th>발주일자</th>
                      <th>매출일자</th>
                      <th>매출금액</th>
                      <th>비고</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class='custom-tr'>
                      <td></td>
                      <td><input type='text' class='form-control' style='border:none' placeholder="발주번호" name='order_no' value="<?php echo isset($order_info['order_no']) ? $order_info['order_no'] : ''; ?>" required></td>
                      <td><input type='date' class='form-control' name='order_date[]' value="<?php echo isset($order_info['order_date']) ? $order_info['order_date'] : date('Y-m-d'); ?>" required></td>
                      <td><input type='date' class='form-control' style='border:none' placeholder='일자' name='sales_date[]' value="<?php echo isset($detail['sales_date']) ? $detail['sales_date'] : date('Y-m-d'); ?>"></td>
                      <td><input type='text' class='form-control text-end' style='border:none' placeholder='금액' oninput="applyFormatNumber(this)" name='amt[]' value="<?php echo isset($detail['amt']) ? number_format($detail['amt']) : ''; ?>"></td>
                      <td><input type='text' class='form-control' style='border:none' placeholder='비고' name='sales_remark[]' value="<?php echo isset($detail['sales_remark']) ? $detail['sales_remark'] : ''; ?>"></td>
                          <td>
                            <button type='button' class='btn btn-outline-success' style='font-size: .65rem' id="confirmButton">
                              <?php echo (isset($detail['condit']) && $detail['condit'] == '완료') ? '완료' : '입금 확인'; ?>
                            </button>
                        </td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>
          </section>
      </div>
    </div>
  </div>
</div>   
<?php
// 분할 매출 정보 입력 
function fetchOrderSales($conn, $order_no) {
    // '분할 매출' 데이터만 가져오도록 쿼리 
    $query = "SELECT * FROM order_data LEFT JOIN sales_data ON order_data.order_no = sales_data.order_no WHERE order_data.order_no = ? AND order_data.condit = '분할' ORDER BY order_data.o_no ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $order_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $sales_data = [];
    while ($row = $result->fetch_assoc()) {
        $sales_data[] = $row;
    }
    return $sales_data;

}
// 데이터와 최대 serial_no 가져오기
$order_no = $_GET['order_no'] ?? 'default_order_no';  // 발주 번호 가져오기
$sales_data = fetchOrderSales($conn, $order_no);  // 함수 호출 


// 분할 매출 총액 계산
$totalSalesRate = 0;
$totalPartialSales = 0;
foreach ($order_data as $sale) {
    if ($sale['condit'] !== '완료' && $sale['condit'] !== '일시불') {
        $totalPartialSales += (float)$sale['amt'];  // 문자열을 숫자형으로 변환
    }
}
?>
<div class='bg-info bg-opacity-10 mt-1' style="width: 1920px; margin: 0 auto;">
  <div class='S_container mt-0'>
    <div class='row justify-content-center'>
      <div class='bg-warning bg-opacity-10'>
        <section class="shadow-lg p-2 my-1 rounded-3 container text-center">
          <!-- Flexbox 컨테이너 -->
          <div class="header-container col-12">
            <h6 class='mt-1'>분할 매출 정보</h6>
            <h6>발주번호: <?php echo htmlspecialchars($order_no); ?></h6> <!-- 사양 출력 -->
            <h6>총 분할매출 금액: <?php echo number_format($totalPartialSales); ?> 원</h6> <!-- 금액 출력 -->              
          </div>
          <div class='container-fluid' style='width: 1100px me-1 ms-1 mt-0'>
            <form action="sales_process.php" method='post' id="salesForm">
              <input type="hidden" name="action_type" value="save_sales">
              <input type="hidden" name="order_no" value="<?php echo $order_no; ?>">              
              <table class='table table-bordered mt-0' style="font-size: .65rem; width: 1000px;">
                <thead style="height: 10px; text-align: center;">
                  <tr class='table table-warning custom-tr'>
                    <th style="width: 8%;">차수</th>
                    <th style="width: 12%;">일자</th>
                    <th style="width: 20%;">금액</th>
                    <th style="width: 10%;">%</th>
                    <th style="width: 40%;">비고</th>
                    <th style="width: 10%;"><button type="button" id="addSalesButton" class="btn btn-extra-sm" onclick="BtnAdd_s()"><i class="fa-solid fa-plus"></i></button></th>
                  </tr>
                </thead>
                <tbody id='salesItemBody'>
                <?php
                $totalSalesAmount = 0;
                $totalSalesRate = 0;
                if (count($sales_data) > 0) {
                    foreach ($sales_data as $detail) { 
                    
                          $currentAmt = isset($detail['sales_amt']) ? $detail['sales_amt'] : 0;
                          $currentRate = isset($detail['sales_rate']) ? str_replace('%', '', $detail['sales_rate']) : 0;
                          $currentRate = floatval($currentRate); // 문자열을 실수로 변환

                          $totalSalesAmount += $currentAmt;
                          $totalSalesRate += $currentRate;
                        ?>
                  <!-- var_dump($detail); -->
                    <tr id='salesItemRow' class='custom-tr'>
                      <td><input type='text' class='form-control' style='border:none text-align:center' placeholder='차수' name='serial_no[]' value="<?php echo isset($detail['serial_no']) ? $detail['serial_no'] : '1'; ?>"></td>
                      <td><input type='date' class='form-control' style='border:none' placeholder='일자' name='sales_date[]' value="<?php echo isset($detail['sales_date']) ? $detail['sales_date'] : date('Y-m-d'); ?>"></td>
                      <td><input type='text' class='form-control text-end' style='border:none' placeholder='금액' oninput="applyFormatNumber(this)" name='sales_amt[]' value="<?php echo isset($detail['sales_amt']) ? number_format($detail['sales_amt']) : ''; ?>"></td>
                      <td><input type='text' class='form-control' style='border:none text-right' placeholder='%' name='sales_rate[]' value="<?php echo isset($detail['sales_rate']) ? $detail['sales_rate'] : ''; ?>"></td>
                      <td><input type='text' class='form-control' style='border:none' placeholder='비고' name='sales_remark[]' value="<?php echo isset($detail['sales_remark']) ? $detail['sales_remark'] : ''; ?>"></td>
                      <td><button type='button' id='delete-db-Button' class="delete-btn btn-extra-sm" style='border:none; font-size: .65rem'><i class="fa-solid fa-trash fs-6"></i></button></td>
                    </tr>
                <?php }
                } else {
                  echo "<tr id='salesItemRow' class='custom-tr'>";
                  echo "<td><input type='text' class='form-control serial_no' style='border:none text-center' placeholder='차수' name='serial_no[]' value='1'></td>";
                  echo "<td><input type='date' class='form-control' style='border:none' placeholder='일자' name='sales_date[]'></td>";
                  echo "<td><input type='text' class='form-control text-end' style='border:none' placeholder='금액' oninput='applyFormatNumber(this)' name='sales_amt[]'></td>";
                  echo "<td><input type='text' class='form-control' style='border:none' placeholder='%' name='sales_rate[]'></td>";
                  
                  echo "<td><input type='text' class='form-control' style='border:none' placeholder='비고' name='sales_remark[]'></td>";
                  echo "<td><button type='button' id='deleButton' class='delete-ui-btn btn-extra-sm' style='border:none; font-size: .65rem'><i class='fa-solid fa-trash fs-6'></i></button></td>";
                  echo "</tr>";
                }
                ?>
                </tbody>
              </table>
              <div class='header-container'>
                <h6 id="totalSalesAmount">누적 매출금액: <?php echo number_format($totalSalesAmount); ?>원</h6>
                <h6 id="totalSalesRate">누적 매출비율: <?php echo $totalSalesRate; ?>%</h6>
                <button type='submit' class='btn btn-success' style='font-size: .65rem'>저장</button>
                <button type='button' class='btn btn-warning' style='font-size: .65rem; margin-left: 10px;' onclick="confirmAndClose()">Close</button>
              </div>
            </div>
          </div>
        </form>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>  
<script>
var totalPartialSales = <?php echo json_encode($totalPartialSales); ?>;
</script>
<script src="js/order.js"></script>
<script src="js/sales.js"></script> 
<script>
//분할 매출 금액 입력 시 누적 매출 비율 계산
document.addEventListener('DOMContentLoaded', function () {
  // 이벤트 위임을 사용하여 동적으로 추가된 입력 필드에도 이벤트 리스너를 적용
  document
    .getElementById('salesItemBody')
    .addEventListener('input', function (event) {
      if (event.target.name === 'sales_amt[]') {
        console.log('금액 입력 변경 감지:', event.target.value);
        const salesAmt = parseFloat(event.target.value.replace(/,/g, ''));
        const salesRateInput = event.target
          .closest('tr')
          .querySelector('input[name="sales_rate[]"]');

        if (salesRateInput && !isNaN(salesAmt) && totalPartialSales > 0) {
          const percentage = (salesAmt / totalPartialSales) * 100;
          salesRateInput.value = percentage.toFixed(2) + '%';
        } else if (salesRateInput) {
          salesRateInput.value = '';
        }
      }
    });
});
</script>
