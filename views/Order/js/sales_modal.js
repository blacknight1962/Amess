// NPO 단가 * 수량 = 합계
function Calc_installment(v) {
  let $row = $(v).closest('tr');
  let priceInput = $row.find('input[name="price[]"]').val().replace(/,/g, '');
  let qtyInput = $row.find('input[name="qty[]"]').val().replace(/,/g, '');

  // qty 값이 비어있으면 기본값으로 1을 설정
  if (!qtyInput) {
    qtyInput = '1'; // 기본 수량을 1로 설정
    $row.find('input[name="qty[]"]').val(qtyInput);
  }

  let price = parseFloat(priceInput);
  let qty = parseFloat(qtyInput);

  let amt = price * qty;

  if (!isNaN(amt)) {
    $row.find('input[name="amt[]"]').val(formatNumber(amt.toFixed(0)));
  }

  GetInTotal_installment();
}
// 전체 합계 계산
function GetInTotal_installment() {
  let sum = 0;
  $('#SA-Body')
    .find('input[name="amt[]"]')
    .each(function () {
      let amtValue = $(this).val().replace(/,/g, ''); // 콤마 제거
      // console.log('amtValue:', amtValue); // 각 amt 값 로그 출력
      let amt = parseFloat(amtValue) || 0;
      if (!isNaN(amt)) {
        sum += amt;
      }
    });

  $('#NPOFTotal').val(formatNumber(sum.toFixed(0))); // 콤마 추가하여 전체 합계 업데이트
}

// 숫자를 콤마로 분리하는 함수
function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
// nonePO 모달의 price 입력 처리
$(document).on('input', '#SA-Body input[name="price[]"]', function () {
  applyFormatNumber(this);
  Calc_installment(this); // 합계 계산 함수 호출
});

function updatePrice_installment(input) {
  var value = input.value.replace(/,/g, ''); // 콤마 제거
  input.value = formatNumber(value); // 콤마 다시 추가
  updateLineTotal_installment(input); // amt 계산 추가
}

function updateLineTotal_installment(input) {
  var $row = $(input).closest('tr');
  var price =
    parseFloat($row.find('input[name="price[]"]').val().replace(/,/g, '')) || 0;
  var qty =
    parseFloat($row.find('input[name="qty[]"]').val().replace(/,/g, '')) || 0;
  var amt = price * qty;
  $row.find('input[name="amt[]"]').val(formatNumber(amt.toFixed(0))); // amt 필드 업데이트

  updateTotal_installment(); // 전체 합계 업데이트
}

function updateTotal_installment() {
  var total = 0;
  document.querySelectorAll('.amt').forEach(function (amtInput) {
    var value = parseFloat(amtInput.value.replace(/,/g, '')); // 콤마를 제거하고 숫자로 변환
    if (!isNaN(value)) {
      total += value;
    }
  });
  document.getElementById('NPOFTotal').value = formatNumber(total.toFixed(0)); // 결과를 포맷하여 총합에 표시
}
//매출 행 추가
$(document).ready(function () {
  $('#addSalesButton').on('click', function () {
    console.log('addSalesButton clicked');
  });
});
//모달내 행 추가 이벤트핸들러
$(document).ready(function () {
  // 'Add SA Row' 버튼 클릭 이벤트 핸들러 추가
  $('#addSAButton').on('click', function () {
    console.log('addSAButton clicked');
    SA_BtnAdd(); // SA_BtnAdd 함수 호출
  });
});

//nonePO 모달내 행추가
function SA_BtnAdd() {
  var newRow = $('#SA_Row').clone(true).appendTo('#SA-Body'); // 이벤트 핸들러와 데이터를 포함하여 복제
  newRow.removeAttr('id');
  newRow.find('input[type="text"], input[type="number"], textarea').val('');
  newRow.find('select').prop('selectedIndex', 0);
  var rowCount = $('#SA-Body tr').length;
  newRow.find('.o_no').val(rowCount);

  // 복제된 행에 CSS 스타일 적용
  newRow.find('td').css('padding', '0px');

  // 콤마 적용 이벤트 핸들러 재설정
  newRow.find('input[name="price[]"]').on('input', function () {
    updatePrice(this); // 콤마 추가 및 amt 계산
  });

  updateSubNos();
}

function updateSubNos() {
  $('#SA-Body tr').each(function (index) {
    $(this)
      .find('.o_no')
      .val(index + 1);
  });
}
console.log('Row numbers updated');

//nonePO 모달 행 삭제
function BtnDel_SA(v) {
  $(v).closest('tr').remove(); // 행 삭제
  updateSubNos(); // 번호 업데이트
}

$('#myModal').on('hidden.bs.modal', function () {
  // 모달 관련 이벤트 핸들러 제거
  $('#addSAButton').off('click');
  // 필요한 경우 추가적인 정리 작업 수행
});
//분할매출 섹션 표시 및 숨김
document.addEventListener('DOMContentLoaded', function () {
  var conditElement = document.getElementById('condit');
  if (conditElement) {
    conditElement.addEventListener('change', function () {
      var conditValue = this.value;
      var installmentDetails = document.getElementById('installmentDetails');
      var numInstallmentsInput = document.getElementById('numInstallments');

      if (conditValue === '분할') {
        installmentDetails.style.display = 'block';
        installmentDetails.style.backgroundColor = '#f8d7da';
      } else {
        installmentDetails.style.display = 'none';
        installmentDetails.style.backgroundColor = '';
      }
    });
  } else {
    console.log('Element with id "condit" not found.');
  }
});

//분할매출 금액 및 합계

//분할매출 섹션 배경색 변경
function changeBackground(selectElement) {
  var parentDiv = selectElement.closest('.container-a'); // 부모 컨테이너를 찾습니다.
  if (selectElement.value === 'Installment') {
    parentDiv.style.backgroundColor = '#f0f8ff'; // 분할을 선택했을 때의 배경색
  } else {
    parentDiv.style.backgroundColor = ''; // 기본 배경색
  }
}

//nonePO 분할매출 테이블
document.addEventListener('DOMContentLoaded', function () {
  var conditElement = document.getElementById('condit');
  if (conditElement) {
    conditElement.addEventListener('change', function () {
      var numInstallmentsInput = document.getElementById('numInstallments');
      if (this.value === '분할') {
        numInstallmentsInput.disabled = false;
      } else {
        numInstallmentsInput.disabled = true;
        numInstallmentsInput.value = ''; // 분할이 아닐 때 입력값 초기화
      }
    });
  } else {
    console.log('Element with id "condit" not found');
  }
});

function updateInstallmentTable() {
  var num = document.getElementById('numInstallments').value;
  var tbody = document
    .getElementById('installmentTable')
    .getElementsByTagName('tbody')[0];
  tbody.innerHTML = ''; // 기존 행 삭제
  for (var i = 0; i < num; i++) {
    addInstallmentRow(i + 1);
  }
}

function addInstallmentRow() {
  var tbody = document
    .getElementById('installmentTable')
    .getElementsByTagName('tbody')[0];
  var row = tbody.insertRow();
  var rowCount = tbody.rows.length; // 현재 행의 수를 가져옵니다.

  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(2);
  var cell4 = row.insertCell(3);
  var cell5 = row.insertCell(4);
  var cell6 = row.insertCell(5);

  cell1.innerHTML = `<input type="text" class="form-control serial_no" name="serial_no[]" value="${rowCount}">`;
  cell2.innerHTML =
    '<input type="text" class="form-control text-end price" name="order_price[]" oninput="applyFormatNumber(this)">';
  cell3.innerHTML =
    '<input type="text" class="form-control sales_rate" name="order_sales_rate[]" placeholder="%">';
  cell4.innerHTML =
    '<input type="date" class="form-control sales_date" name="order_sales_date[]">';
  cell5.innerHTML =
    '<input type="text" class="form-control sales_remark" name="order_sales_remark[]">';
  cell6.innerHTML =
    '<button type="button" class="btn link-danger small-btn" onclick="removeInstallmentRow(this)"><i class="fa-solid fa-trash fs-6"></i></button>';
}

function removeInstallmentRow(button) {
  var row = button.parentNode.parentNode;
  var tbody = row.parentNode;
  tbody.removeChild(row);

  // 모든 행의 serial_no 업데이트
  updateSerialNumbers();
}

function updateSerialNumbers() {
  var rows = document.querySelectorAll('#installmentTable tbody tr');
  rows.forEach((row, index) => {
    var inputSerialNo = row.querySelector('.serial_no');
    if (inputSerialNo) {
      inputSerialNo.value = index + 1; // 행 인덱스에 1을 더해 serial_no를 업데이트합니다.
    }
  });
}
//sales_rate 입력 시 % 추가
document.addEventListener('DOMContentLoaded', function () {
  const salesRateInputs = document.querySelectorAll('.sales_rate');

  salesRateInputs.forEach((input) => {
    input.addEventListener('input', function () {
      let value = this.value.replace(/[^0-9.]/g, ''); // 숫자와 소수점만 남깁니다.
      this.value = value + (value ? '%' : ''); // 값이 있으면 %를 붙입니다.
    });

    input.addEventListener('blur', function () {
      if (this.value === '%') {
        this.value = ''; // 입력이 없을 때 %만 남지 않도록 처리
      }
    });
  });
});
