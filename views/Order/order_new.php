<?php
$pageTitle = "발주관리-발주등록";
include('include/header.php');
include(__DIR__ . '/../../db.php');

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
        <h4 class='bg-primary bg-opacity-10 justify-content-center text-center p-2'>
            발주관리 - <?php echo isset($order_no) && $order_no != "" ? '정보 UPDATE' : '신규등록'; ?></h4>
          <section class="shadow-lg p-1 my-1 rounded-3 container text-center">
            <h6 class='mt-1'>기본정보</h6>
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
                  <tbody>
                    <tr class='custom-tr'>
                      <td><input type='text' class='form-control' style='border:none' placeholder="발주번호" name='order_no' value="<?php echo isset($order_info['order_no']) ? $order_info['order_no'] : ''; ?>" required></td>
                      <td><?= createSelectOrderCustomer($conn, isset($order_info['order_custo']) ? $order_info['order_custo'] : ''); ?></td>
                      <td><?= createSelectCustomer($conn, isset($order_info['customer']) ? $order_info['customer'] : ''); ?></td>
                      <td><input type='date' class='form-control' name='order_date' value="<?php echo isset($order_info['order_date']) ? $order_info['order_date'] : date('Y-m-d'); ?>" required></td>
                      <td><input type='text' class='form-control' placeholder='담당자' name='custo_name' value="<?php echo isset($order_info['custo_name']) ? $order_info['custo_name'] : ''; ?>"></td>
                      <td><input type='text' class='form-control' placeholder='특기사항' name='specifi' value="<?php echo isset($order_info['specifi']) ? $order_info['specifi'] : ''; ?>"></td>
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
  <div class='row justify-content-center' style="max-width: 1920px; margin: 0 auto;">
  <h6 class='mt-2' style="text-align: center;">상세정보</h6>
  <section class="shadow-lg mt-0 p-2 pt-0 my-0 rounded-3 container-fluid justify-content-center text-center ms-0">
    <div class='container-fluid' style='width: 1890px; padding-left: 0;'>
      <form action="order_process.php" method='post'>
        <input type='hidden' name='action_type' value='save_detail'>
        <input type='hidden' name='order_no' value='<?php echo $order_no;ENT_QUOTES ?>'>
          <table table class='table table-bordered mt-1' style="font-size: .65rem; width: 1900px;">
            <thead style="text-align: center;">
              <tr class='table table-warning custom-tr'>
                <th style="width: 3%;">No</th>
                <th style="width: 5%;">부서</th>
                <th style="width: 5%;">구분</th>
                <th style="width: 9%;">자재코드</th>
                <th style="width: 13%;">품명</th>

                <th style="width: 12%;">사양</th>
                <th style="width: 8%;">요청납기</th>
                <th style="width: 7%;">단 가</th>
                <th style="width: 5%;">단위</th>
                <th style="width: 4%;">수량</th>

                <th style="width: 8%;">합계(원화)</th>
                <th style="width: 4%;">환율</th>
                <th style="width: 8%;">매출예정일자</th>
                <th style="width: 6%;">조건(%)</th>
                <th style="width: 3%;"><button type="button" id="addButton" class="btn btn-sm" style="font-size: .65rem" onclick="BtnAdd_o()"><i class="fa-solid fa-plus"></i></button></th>
              </tr>
            </thead>
            <tbody id="orderItemBody">
              <?php
              $order_data = fetchOrderDetails($conn, $order_no);
              if (count($order_data) > 0) {
                  foreach ($order_data as $detail) { ?>
                    <tr id='orderItemRow' class='custom-tr'>
                      <td><input type='text' class='form-control' style='border:none' name='o_no[]' value="<?php echo isset($detail['o_no']) ? htmlspecialchars($detail['o_no']) : ''; ?>"></td>
                      <td><input type='text' class='form-control' style='border:none' name='picb[]' value="<?php echo isset($detail['picb']) ? htmlspecialchars($detail['picb']) : ''; ?>"></td>
                      <td><input type='text' class='form-control' style='border:none' name='aparts[]' value="<?php echo isset($detail['aparts']) ? htmlspecialchars($detail['aparts']) : ''; ?>"></td>
                      <td><input type='text' class='form-control' style='border:none' name='parts_code[]' value="<?php echo isset($detail['parts_code']) ? htmlspecialchars($detail['parts_code']) : ''; ?>"></td>
                      <td><input type='text' class='form-control' style='border:none' name='product_na[]' value="<?php echo isset($detail['product_na']) ? htmlspecialchars($detail['product_na']) : ''; ?>"></td>
                      <td><input type='text' class='form-control' style='border:none' name='product_sp[]' value="<?php echo isset($detail['product_sp']) ? htmlspecialchars($detail['product_sp']) : ''; ?>"></td>
                      <td><input type='date' class='form-control' style='border:none' name='requi_date[]' value="<?php echo isset($detail['requi_date']) ? htmlspecialchars($detail['requi_date']) : ''; ?>"></td>
                      <td><input type='text' class='form-control price small-input right-align' style='border:none' name='price[]' value="<?php echo isset($detail['price']) ? htmlspecialchars(number_format($detail['price'])) : ''; ?>" oninput="updatePrice(this)"></td>
                      <td><input type='text' class='form-control' style='border:none' name='currency[]' value="<?php echo isset($detail['currency']) ? htmlspecialchars($detail['currency']) : ''; ?>"></td>
                      <td><input type='number' class='form-control' style='border:none' name='qty[]' value="<?php echo isset($detail['qty']) ? htmlspecialchars($detail['qty']) : ''; ?>" oninput="updateLineTotal(this)"></td>
                      <td><input type='text' class='form-control amt small-input right-align' style='border:none' name='amt[]' value="<?php echo isset($detail['amt']) ? htmlspecialchars(number_format($detail['amt'])) : ''; ?>"></td>
                      <td><input type='text' class='form-control' style='border:none' name='curency_rate[]' value="<?php echo isset($detail['curency_rate']) ? htmlspecialchars($detail['curency_rate']) : ''; ?>"></td>
                      <td><input type='date' class='form-control' style='border:none' name='sales_date[]' value="<?php echo isset($detail['sales_date']) ? htmlspecialchars($detail['sales_date']) : ''; ?>"></td>
                      <td><select class="form-select" id='condit' name='condit[]' aria-label="" style="font-size: .65rem">
                          <option value="선택" <?php echo (!isset($detail['condit']) || $detail['condit'] == '선택') ? 'selected' : ''; ?>>선택</option>
                          <option value="일시불" <?php echo (isset($detail['condit']) && $detail['condit'] == '일시불') ? 'selected' : ''; ?>>일시불</option>
                          <option value="분할" <?php echo (isset($detail['condit']) && $detail['condit'] != '일시불') ? 'selected' : ''; ?>>분할</option>
                        </select>
                      </td>
                      <td><button type='button' id='delButton' class='btn btn-extra-sm' style='font-size: .65rem' onclick='BtnDel_o(this)'><i class="fa-solid fa-trash fs-6"></i></button></td>
                    </tr>
                    <?php
                  }
              } else {
                  // 데이터가 없을 때만 기본 행을 출력
                  echo "<tr id='orderItemRow'>";
                  echo "<td><input type='text' class='form-control o_no' style='border:none' name='o_no[]' value='1'></td>";
                  echo "<td>".createSelectPicb($conn, 'division', 'picb', 'picb', 'picb[]')."</td>";
                  echo "<td>".createSelectOptions($conn, 'apart', 'aparts', 'aparts', 'aparts[]')."</td>";
                  echo "<td><input type='text' style='border:none' class='form-control' placeholder='자재코드' name='parts_code[]'></td>";
                  echo "<td><input type='text' style='border:none' class='form-control' placeholder='품명' name='product_na[]'></td>";
                  echo "<td><input type='text' style='border:none' class='form-control' placeholder='사양' name='product_sp[]'></td>";
                  echo "<td><input type='date' style='border:none' class='form-control' placeholder='요청납기' name='requi_date[]'></td>";
                  echo "<td><input type='text' style='border:none; font-size:14px' class='form-control text-end price' name='price[]' onchange='Calc(this);' value=''></td>";
                  echo "<td>".createSelectCurrency($conn, 'currency', 'currency', 'currency', 'currency[]')."</td>";
                  echo "<td><input type='number' style='border:none; font-size:14px' class='form-control text-end qty' name='qty[]' onchange='Calc(this);' value=''></td>";
                  echo "<td><input type='text' style='border:none; font-size:14px' class='form-control text-end formatNumber' name='amt[]' onchange='Calc(this);' value='' readonly></td>";
                  echo "<td><input type='int' style='border:none' class='form-control' placeholder='환율' name='curency_rate[]'></td>";
                  echo "<td><input type='date' style='border:none' class='form-control' placeholder='매출일자' name='sales_date[]'></td>";
                  echo "<td><select class='form-select' id='condit' name='condit[]' onchange='changeBackground(this)' aria-label='' style='font-size: .65rem' value=''>
                              <option selected>선택</option>
                              <option value='일시불'>일시불</option>
                              <option value='분할'>분할</option>
                            </select>
                          </td>";
                  echo "<td><button type='button' id='delButton' class='btn btn-extra-sm' style='font-size: .65rem' onclick='BtnDel_o(this)'><i class='fa-solid fa-trash fs-6'></i></button></td>";
                  echo "</tr>";
              }
              ?>
            </tbody>
        </table>
        <div class='row' style="margin-top: 2px;">
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
            <button type='submit' class='btn btn-outline-success btn-sm' style='font-size: .65rem'>저장</button>
          </div>
        </div>
      </div>
    </form>
    </div>
  </section>
  </div>
</div>
<!-- 여기서 부터  분할 매출 등록 화면 -->
<?php
$InTotal = 0;
?>
<div class='bg-info bg-opacity-10 mt-1' style="width: 1000px; margin: 0 auto;">
<section class="shadow-lg p-2 my-2 rounded-3 container text-center">
  <div class='container-a mt-1'>
    <div class='row justify-content-end'> <!-- 여기를 수정했습니다 -->
      <div class='bg-warning bg-opacity-10' style="width: 1000px;"> <!-- 너비를 명시적으로 설정할 수 있습니다 -->
        <div id="installmentDetails" style="border-top: 2px solid #ccc;">
          <div class="d-flex justify-content-between align-items-center">
            <h6 id="installmentHeader" style="margin-top: 2px;" class="mb-0">분할매출 내용 입력</h6>
            <input type="number" id="numInstallments" name="num_installments" class="form-control" style="width: auto; margin-top: 10px; font-size: .65rem;" placeholder="분할 횟수 입력" onchange="updateInstallmentTable()">
          </div>
          <form action="order_process.php" method='post' id="salesForm">
            <input type="hidden" name="action_type" value="save_sales">
            <input type="hidden" name="order_no" value="<?php echo $order_no; ?>"> 
            <table id="installmentTable" style="font-size: .65rem; width: 100%;">
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
                  <td><input type="text" class="form-control serial_no" name="serial_no[]" placeholder="차수"></td>  
                  <td><input type='text' class='form-control text-end price' name='price[]' oninput='applyFormatNumber(this); updateInTotal();' value=''></td>
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
<div class="modal-footer">
<button type="submit" class="btn btn-outline-success btn-sm" style="font-size: .65rem;">저장</button>
</section>
<script src="js/order.js"></script>
<script src="js/sales_modal.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>
<?php include('include/footer.php'); ?>

