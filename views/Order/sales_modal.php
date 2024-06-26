<?php
$order_no = isset($order_no) ? $order_no : 'default_order_no';
$o_no = isset($o_no) ? $o_no : 'default_o_no';
?>

<div class="modal fade custom-modal-size" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header">
        <?php
        $noPO = createSelectNoPO($conn); // 관리번호 생성
        echo "<h6 class='modal-title'>nonePO매출 등록.(관리번호: {$noPO}).</h6>"; // 관리번호를 타이틀에 포함
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style='text-align: center; padding: 4px;'>
        <form action="order_process.php" method="post">
          <input type="hidden" name="action_type" value="nonePO">
            <?php
            $noPO = createSelectNoPO($conn); // 관리번호 생성
            echo "<input type='hidden' name='order_no' value='{$noPO}'>"; // 숨겨진 필드로 관리번호 설정
            ?>
          <table class='table table-bordered' style='font-size: .65rem; margin-top: 4px; margin-bottom: 4px;'>
            <thead style='text-align: center;'>
              <tr class='table-primary'>
                <th style='width: 3%;'>#</th>
                <th style='width: 4%;'>부서</th>
                <th style='width: 8%;'>고객사</th>
                <th style='width: 8%;'>자재코드</th>
                <th style='width: 10%;'>품명</th>

                <th style='width: 10%;'>사양</th>
                <th style='width: 7%;'>요청납기</th>
                <th style='width: 6%;'>단가</th>
                <th style='width: 5%;'>단위</th>
                <th style='width: 5%;'>수량</th>

                <th style='width: 6%;'>합계</th>
                <th style='width: 5%;'>환율</th>
                <th style='width: 6%;'>매출예정일자</th>
                <th style='width: 6%;'>조건</th>
                <th style='width: 3%;'><button type="button" style="font-size: .75rem; border: none;" id="addSAButton">+</button></th>
              </tr>
            </thead>
            <tbody id='SA-Body'  style='padding: 0px;'>
              <tr id='SA_Row'>
                <td><input type="text" class="form-control o_no" name="o_no[]" style='border:none; text-align: center;' value="1"></td>
                <td><?php echo createSelectPicb($conn, 'division', 'picb', 'picb', 'picb[]'); ?></td>

                <td><?php echo createSelectOrderCustomer($conn, false); ?></td>
                <td><input type="text" class="form-control parts_code" name="parts_code[]" placeholder="자재코드"></td>
                <td><input type="text" class="form-control product_na" name="product_na[]" placeholder="품명"></td>

                <td><input type="text" class="form-control product_sp" name="product_sp[]" placeholder="사양"></td>
                <td><input type="date" class="form-control requi_date" name="requi_date[]" placeholder="요청납기"></td>
                <td><input type='text' style='border:none; font-size:14px' class='form-control text-end price' name='price[]' onchange='Calc_installment(this);' value=''></td>
                <td><input type="text" class="form-control" name="currency[]" placeholder="환율"></td>
                <td><input type='number' style='border:none; font-size:14px' class='form-control text-end qty' name='qty[]' onchange='Calc_installment(this);' value=''></td>

                <td><input type='text' style='border:none; font-size:14px' class='form-control text-end formatNumber' name='amt[]' onchange='Calc_installment(this);' value='' readonly></td>
                <td><input type="text" class="form-control curency_rate" name="curency_rate[]" placeholder="환율"></td>
                <td><input type="date" class="form-control sales_date" name="sales_date[]" placeholder="매출일자"></td>
                <td><select class="form-select condit" name="condit[]" style="font-size: .65rem;" id="condit">
                        <option value="선택">선택</option>
                        <option value="일시불">일시불</option>
                        <option value="분할">분할</option>
                    </select>
                </td>
                <td><button type="button" class="btn link-danger small-btn" style="font-size: .65rem" onclick="BtnDel_SA(this)">
                          <i class="fa-solid fa-trash fs-6"></i></button></td>
              </tr>
              <tr>
                <td colspan="10" class="text-right"><span style="font-size: .75rem;">NonePO매출 합계</span></td>
                <td colspan="2"><input type='text' class='form-control text-end large-bold-text' style="width: 150px;" id='NPOFTotal' name='NPOFTotal' disabled=''/></td>
              </tr>
                        
          
            </tbody>
          </table>

        <?php
        $InTotal = 0;
        ?>
          <hr style="border-top: 2px solid #ccc;">
          <div id="installmentDetails" style="border-top: 2px solid #ccc;">
            <h6 id="installmentHeader">분할매출 내용 입력</h6>
              <div class="row">
                <div class="col-4"></div>
                <div class="col-2">
                  <input type="number" id="numInstallments" style="font-size: .65rem;" placeholder="분할 횟수 입력" onchange="updateInstallmentTable()">
                </div>
                <div class="col-6">
                <table id="installmentTable" style="font-size: .65rem; width: 100%;">
                  <thead>
                      <tr>
                          <th>분할 #</th>
                          <th>금액</th>
                          <th>비율</th>
                          <th>예정일</th>
                          <th>비고</th>
                          <th>삭제</th>
                      </tr>
                  </thead>
                  <tbody id="installmentBody">
                    <tr>
                      <td><input type="text" class="form-control serial_no" name="serial_no[]" placeholder="차수"></td>  
                      <td><input type='text' class='form-control text-end order_price' name='order_price[]' onchange='applyFormatNumber(this)' value=''></td>
                      <td><input type='text' class='form-control sales_rate' name='order_sales_rate[]' id='salesRateInput'>
                      <td><input type="date" class="form-control sales_date" name="order_sales_date[]" placeholder="%"></td>
                      <td><input type='text' class='form-control sales_remark' placeholder='비고' name='order_sales_remark[]'></td>
                      <td><button type="button" class="btn link-danger small-btn" style="font-size: .65rem;" onclick="BtnDel_SA(this)">
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
        <button type="submit" class="btn btn-primary btn-sm" style="font-size: .65rem;">Save</button>
        <button type="button" class="btn btn-secondary btn-sm" style="font-size: .65rem;" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src='js/sales_modal.js'></script>
<script>

  // 이벤트 위임을 사용하여 동적 요소에 대응
// 분할매출의 price 입력 처리
$(document).on('input', '#installmentTable input[name="order_price[]"]', function() {
  applyFormatNumber(this);
  updateTotal_installment(this);  // 분할매출 합계 계산 함수 호출
});

  // 전체 문서에 대한 입력 이벤트 리스너 설정
  $(document).on('input', function(event) {
    
  });

  function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  function applyFormatNumber(inputElement) {
    var originalValue = inputElement.value;


    var formattedValue = formatNumber(inputElement.value.replace(/,/g, ''));
  

    inputElement.value = formattedValue;
    updateTotal_installment();
  }

function updateTotal_installment() {
  let total = 0;
  $('#installmentTable input[name="order_price[]"]').each(function() {
    let value = parseFloat($(this).val().replace(/,/g, ''));
    if (!isNaN(value)) {
      total += value;
    }
  });
  $('#InTotal').val(formatNumber(total.toFixed(0)));
}

</script>

