<?php
$pageTitle = "발주관리-발주등록";
include('include/header.php');
include(__DIR__ . '/../../db.php');

function fetchOrderInfo($conn, $order_no) {
    $order_no = trim($order_no); // 앞뒤 공백 제거
    $query = "SELECT * FROM `order` WHERE order_no = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('쿼리 준비 실패: ' . $conn->error);
    }
    $stmt->bind_param("s", $order_no); // 문자열로 처리
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        die('쿼리 실행 오류: ' . $conn->error);
    }
    $order_info = $result->fetch_assoc(); // 단일 행만 반환
    error_log("Order info fetched: " . print_r($order_info, true)); // 디버깅 코드 추가
    return $order_info;
}

$order_info = [];
$order_no = ""; // 초기 order_no를 빈 문자열로 설정
$o_no = 1;

if (isset($_GET['order_no'])) {
    $order_no = $_GET['order_no'];
    $order_info = fetchOrderInfo($conn, $order_no);
}
?>
<!-- 발주 기본정보 입력 -->
<div class='bg-info bg-opacity-10 mt-1' style="width: 100%; margin: 0 auto;">
  <div class='container mt-1' style="max-width: 1450px;">
    <div class='row justify-content-center'>
      <div class='bg-warning bg-opacity-10' style="width: 100%;">
        <h4 class='bg-primary bg-opacity-10 justify-content-center text-center p-2'>
            발주관리 - <?php echo isset($order_no) && $order_no != "" ? '정보 UPDATE' : '신규등록'; ?></h4>
          <section class="shadow-lg p-1 my-1 rounded-3 container text-center" style="max-width: 1400px;">
            <h6 class='mt-1'>기본정보</h6>
            <div class='container-fluid' style='width: 100%;'>
              <form action="order_process.php" method='post'>
                <input type="hidden" name="action_type" value="save_basic">
                <table class='table table-bordered mt-1' style="font-size: .75rem; width: 100%;">
                  <thead style="text-align: center;">
                    <tr class='table table-warning'>
                      <th style="width: 10%;">발주번호</th>
                      <th style="width: 10%;">발주사</th>
                      <th style="width: 13%;">고객사</th>
                      <th style="width: 10%;">발주일자</th>
                      <th style="width: 10%;">담당자</th>
                      <th style="width: 13%;">생산코드</th>
                      <th style="width: 12%;">착수일자</th>
                      <th style="width: 10%;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class='custom-tr'>
                      <td><input type='text' class='form-control' style='border:none' placeholder="발주번호" name='order_no' value="<?php echo isset($order_info['order_no']) ? $order_info['order_no'] : ''; ?>" required></td>
                      <td><?= createSelectOrderCustomer1($conn, isset($order_info['order_custo']) ? $order_info['order_custo'] : ''); ?></td> 
                      <td><?= createSelectCustomer1($conn, isset($order_info['customer']) ? $order_info['customer'] : ''); ?></td>
                      <td><input type='date' class='form-control' name='order_date' value="<?php echo isset($order_info['order_date']) ? $order_info['order_date'] : date('Y-m-d'); ?>" required></td>
                      <td><input type='text' class='form-control' placeholder='담당자' name='custo_name' value="<?php echo isset($order_info['custo_name']) ? $order_info['custo_name'] : ''; ?>"></td>
                      <td><input type='text' class='form-control' placeholder='생산코드' name='production_code' value="<?php echo isset($order_info['production_code']) ? $order_info['production_code'] : ''; ?>"></td>
                      <td><input type='date' class='form-control' placeholder='착수일자' name='production_start' value="<?php echo isset($order_info['production_start']) ? $order_info['production_start'] : ''; ?>"></td>
                      <td><button type="submit" id="saveButton" class="btn btn-outline-success btn-sm" style="font-size: .65rem"><?php echo !empty($order_info) ? 'UPDATE' : '저장'; ?></button></td>
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
    $query = "SELECT * FROM `order_data` WHERE order_no = ? ORDER BY o_no ASC";
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
<!-- 발주 상세정보 입력 -->
<div class='bg-info bg-opacity-10' style="width: 1920px; margin: 0 auto;">
  <div class='row justify-content-center' style="max-width: 2000px; margin: 0 auto;">
  <h6 class='mt-2' style="text-align: center;">상세정보</h6>
  <section class="shadow-lg mt-0 p-2 pt-0 my-0 rounded-3 container-fluid justify-content-center text-center ms-0">
    <div class='container-fluid' style='width: 1920px; padding-left: 0;'>
      <form action="order_process.php" method='post'>
        <input type='hidden' name='action_type' value='save_detail'>
        <input type='hidden' name='order_no' value='<?php echo $order_no;ENT_QUOTES ?>'>
        <input type='hidden' name='o_no' value='<?php echo $o_no;?>'>
          <table table class='table table-bordered mt-1' style="font-size: .75rem; width: 1920px;">
            <thead style="text-align: center;">
              <tr class='table table-warning custom-tr'>
                <th style="width: 3%;">No</th>
                <th style="width: 5%;">부서</th>
                <th style="width: 5%;">구분</th>
                <th style="width: 7%;">특기사항</th>
                <th style="width: 7%;">자재코드</th>
                <th style="width: 11%;">품명</th>

                <th style="width: 12%;">사양</th>
                <th style="width: 8%;">요청납기</th>
                <th style="width: 7%;">단 가</th>
                <th style="width: 4%;">단위</th>
                <th style="width: 4%;">수량</th>

                <th style="width: 8%;">합계(원화)</th>
                <th style="width: 4%;">환율</th>
                <th style="width: 8%;">매출예정일자</th>
                <th style="width: 5%;">조건(%)</th>
                <th style="width: 4%;"><button type="button" id="addButton" class="btn btn-sm" style="font-size: .65rem"><i class="fa-solid fa-plus"></i></button></th>
              </tr>
            </thead>

        <tbody id="orderItemBody">
          <tr id='orderItemRow'>
            <td><input type='text' class='form-control o_no' style='border:none' name='o_no[]' value='1'></td>
            <td><?php echo createSelectPicb($conn, 'division', 'picb', 'picb', 'picb[]'); ?></td>
            <td><?php echo createSelectOptions($conn, 'apart', 'aparts', 'aparts', 'aparts[]'); ?></td>
            <td><input type='text' class='form-control specifi', style='border:none' name='specifi[]' placeholder='특기사항' value=''></td>                  
            <td><input type='text' style='border:none' class='form-control' placeholder='자재코드' name='parts_code[]'></td>
            <td><input type='text' style='border:none' class='form-control' placeholder='품명' name='product_na[]'></td>
            <td><input type='text' style='border:none' class='form-control' placeholder='사양' name='product_sp[]'></td>
            <td><input type='date' style='border:none' class='form-control' placeholder='요청납기' name='requi_date[]'></td>
            <td><input type='text' style='border:none; font-size:14px' class='form-control text-end price' name='price[]' onchange='Calc(this);' value=''></td>
            <td><?php echo createSelectCurrency($conn, 'currency', 'currency', 'currency', 'currency[]'); ?></td>
            <td><input type='number' style='border:none; font-size:14px' class='form-control text-end qty' name='qty[]' onchange='Calc(this);' value=''></td>
            <td><input type='text' style='border:none; font-size:14px' class='form-control text-end formatNumber' name='amt[]' onchange='Calc(this);' value='' readonly></td>
            <td><input type='int' style='border:none' class='form-control' placeholder='환율' name='curency_rate[]'></td>
            <td><input type='date' style='border:none' class='form-control' placeholder='매출일자' name='sales_date[]'></td>
            <td><select class='form-select' id='condit' name='condit[]' aria-label='' style='font-size: .65rem' value=''>
                        <option selected>선택</option>
                        <option value='일시불'>일시불</option>
                        <option value='분할'>분할</option>
                      </select>
                    </td>
            <td><button type='button' id='delButton' class='btn btn-extra-sm' style='font-size: .65rem' onclick='BtnDel_o(this)'><i class='fa-solid fa-trash fs-6'></i></button></td>
          </tr>                  
        </tbody>
      </table>
      <?php
      $InTotal = 0;
      ?>
      <!-- 분할 매출 입력 -->
      <div class='bg-info bg-opacity-10 mt-20' style="width: 100%; margin: 0 auto;">

        <section class="shadow-lg p-2 my-4 rounded-3 container text-center">
          <div class='container-a mt-1'>
            <div class='row justify-content-end'>
              <div class='bg-warning bg-opacity-10' style="width: 100%;">
                <div id="installmentDetails" style="border-top: 2px solid #ccc;">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 id="installmentHeader" style="margin-top: 2px;" class="mb-0">분할매출 내용 입력</h6>
                    <input type="number" id="numInstallments" name="num_installments" class="form-control" style="width: auto; margin-top: 10px; font-size: .65rem;" placeholder="분할 횟수 입력" onchange="updateInstallmentTable()">
                  </div>
                  <table id="installmentTable" style="font-size: .75rem; width: 100%;">
                    <thead>
                      <tr>
                        <th>분할 #</th>
                        <th>금액</th>
                        <th>비율</th>
                        <th>매출예정일</th>
                        <th>비고</th>
                        <th>삭제</th>
                      </tr>
                    </thead>
                    <tbody id="installmentBody">
                      <tr>
                        <td><input type="text" class="form-control serial_no" style="text-align: center;" name="serial_no[]" placeholder="차수"></td>  
                        <td><input type='text' class='form-control text-end price' name='installment_price[]' oninput='applyFormatNumber(this);' value=''></td>
                        <td><input type='text' class='form-control sales_rate' name='sales_rate[]' id='salesRateInput' value=''"%"></td>
                        <td><input type="date" class="form-control sales_date" name="sales_date[]" placeholder="매출예정일자" ></td>
                        <td><input type='text' class='form-control sales_remark' placeholder='비고' name='sales_remark[]'></td>
                        <td><button type="button" class="btn link-secondary small-btn" style="font-size: .65rem;" onclick="BtnDel_SA(this)">
                            <i class="fa-solid fa-trash fs-6"></i></button></td>
                      </tr>
                    </tbody>
                  </table>
                  <div class='row'>
                    <div class='col-6'></div>
                    <div class='col-4'>
                      <div class='input-group mb-1'>
                        <span class='input-group-text' style="font-size: .65rem;">분할매출 합계</span>
                        <input type='text' class='form-control text-end large-bold-text' id='InTotal' name='InTotal' value="<?php echo number_format($InTotal); ?>" disabled=''/>
                      </div>
                    </div>
                    <div class='col-2'></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <div class='row' style="margin-top: 2px;">
        <div class='col-1'></div>
        <div class='col-6'></div>
        <div class='col-3'>
          <div class='input-group mb-1'>
            <span class='input-group-text' style="font-size: .65rem;">발주 합계</span>
            <input type='text' class='form-control text-end large-bold-text' id='FTotal' name='FTotal' disabled=''/>
          </div>
        </div>
        <div class='col-2'>
          <button type='submit' class='btn btn-outline-success btn-sm' style='font-size: .65rem' id="saveButton">저장</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/order.js"></script>
<!-- <script src="js/sales_modal.js"></script> -->
<script>

  // 분할 매출 테이블이 사라지는 문제를 디버깅하기 위해 콘솔 로그 추가
  $(document).ready(function() {
    $('#condit').on('change', function() {
      console.log('Condition changed:', $(this).val());
    });
  });

  document.getElementById('orderForm').addEventListener('submit', function(event) {
    var installmentRows = document.querySelectorAll('#installmentBody tr');
    var hasInstallments = false;
    installmentRows.forEach(function(row) {
      var serialNo = row.querySelector('input[name="serial_no[]"]').value;
      if (serialNo.trim() !== '') {
        hasInstallments = true;
      }
    });
    if (hasInstallments) {
      document.querySelector('input[name="action_type"]').value = 'save_sales';
    }
  });
</script>
</body>