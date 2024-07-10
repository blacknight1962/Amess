$(document).ready(function () {
  initializePage();
  calculateAmtTotal(); // 페이지 로드 시 총합 계산
  GetTotal(); // 페이지 로드 시 총합 계산
  attachEditButtonListener(); // 페이지 로드 시 편집 버튼 리스너 추가
});

function initializePage() {
  $('#addButton').on('click', function () {
    console.log('addButton clicked');
    BtnAdd_o(); // BtnAdd_o 함수 호출
  });
  // 각 행의 condit 요소에 이벤트 리스너 추가
  $('#orderItemBody').on('change', 'select[name="condit[]"]', function () {
    handleConditionChange(this);
  });
}

function BtnAdd_o() {
  console.log('BtnAdd_o 함수 호출됨'); // 함수 호출 확인용 로그
  var lastONo = 0;
  var lastRow = $('#orderItemBody tr:last');
  if (lastRow.length > 0 && lastRow.find('.o_no').val()) {
    lastONo = parseInt(lastRow.find('.o_no').val());
  }
  var newONo = lastONo + 1; // 마지막 o_no에서 1을 더합니다.

  let newRow = $('#orderItemRow').clone().appendTo('#orderItemBody');
  newRow.removeAttr('id'); // id 속성 제거

  newRow
    .find('input[type="text"], input[type="number"], input[type="date"]')
    .val(''); // 나머지 필드 초기화
  newRow.find('select').prop('selectedIndex', 0); // 선택 필드 초기화

  newRow.find('.o_no').val(newONo); // 새로운 o_no 설정

  lastONo = newONo; // 전역 변수 업데이트

  // 새로 추가된 행의 이벤트 리스너 설정
  newRow
    .find('input[name="price[]"], input[name="qty[]"]')
    .on('change', function () {
      Calc(this);
    });

  // 새로 추가된 행의 condit 요소에 이벤트 리스너 추가
  newRow.find('select[name="condit[]"]').on('change', function () {
    handleConditionChange(this);
  });
}

function deleteSelectedQuotes() {
  var selectedOrderNos = $('input[type="checkbox"].row-checkbox:checked')
    .map(function () {
      return $(this).val();
    })
    .get();
  if (selectedOrderNos.length > 0) {
    if (confirm('선택한 발주를 삭제하시겠습니까?')) {
      console.log('Deleting orders:', selectedOrderNos);
      $.ajax({
        url: 'update_process.php',
        type: 'POST',
        data: {
          action_type: 'delete_order', // action_type 추가
          order_no: selectedOrderNos.join(','), // 여러 발주번호를 쉼표로 구분된 문자열로 전송
          condit: 'some_value', // 필요에 따라 적절한 값을 할당하세요.
        },
        dataType: 'text', // 응답을 텍스트로 설정
        success: function (response) {
          if (response.trim() === 'success') {
            console.log('발주 삭제 완료');
            location.reload(); // 성공적으로 삭제 후 페이지 새로고침
          } else if (response.startsWith('error:')) {
            console.error('발주 삭제 실패:', response.substring(6).trim());
          } else {
            console.error('발주 삭제 실패: 알 수 없는 오류');
          }
        },
        error: function (xhr, status, error) {
          console.error('발주 삭제 실패:', error);
        },
      });
    }
  } else {
    alert('삭제할 발주를 선택해주세요.');
  }
}

function BtnDel_o(button) {
  console.log('BtnDel_o 함수 호출됨'); // 함수 호출 확인용 로그
  $(button).closest('tr').remove();
  GetTotal(); // 행 삭제 후 총합 계산
}

// GetTotal 함수 정의
function GetTotal() {
  var total = 0;
  $('#orderItemBody tr').each(function () {
    var price = $(this).find('input[name="price[]"]').val().replace(/,/g, '');
    var qty = $(this).find('input[name="qty[]"]').val().replace(/,/g, '');
    price = Number(price) || 0; // 숫자로 변환
    qty = Number(qty) || 0; // 숫자로 변환
    total += price * qty;
  });
  $('#FTotal').val(formatNumber(total));
}

function calculateAmtTotal() {
  var total = 0;
  $('#orderItemBody tr').each(function () {
    var amt = $(this).find('input[name="amt[]"]').val().replace(/,/g, '');
    amt = Number(amt) || 0; // 숫자로 변환
    total += amt;
  });
  $('#FTotal').val(formatNumber(total));
}
//천단위 콤마 삽입
function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// function updatePrice(input) {
//   var value = input.value.replace(/,/g, ''); // 콤마 제거
//   input.value = formatNumber(value); // 콤마 다시 추가
//   updateLineTotal(input); // amt 계산 추가
// }

// function updateLineTotal(input) {
//   var $row = $(input).closest('tr');
//   var price =
//     parseFloat($row.find('input[name="price[]"]').val().replace(/,/g, '')) || 0;
//   var qty =
//     parseFloat($row.find('input[name="qty[]"]').val().replace(/,/g, '')) || 0;
//   var amt = price * qty;
//   console.log('amt:', amt);
//   $row.find('input[name="amt[]"]').val(formatNumber(amt.toFixed(0))); // amt 필드 업데이트

//   updateTotal(); // 전체 합계 업데이트
// }

// function updateTotal() {
//   var total = 0;
//   document.querySelectorAll('input[name="amt[]"]').forEach(function (amtInput) {
//     var value = parseFloat(amtInput.value.replace(/,/g, ''));
//     if (!isNaN(value)) {
//       total += value;
//       console.log('total:', total);
//     }
//   });
//   document.getElementById('FTotal').value = formatNumber(total.toFixed(0)); // 결과를 포맷하여 총합에 표시
// }

function Calc(input) {
  updatePrice(input);
  updateLineTotal(input);
}

// 분할매출 섹션 표시 및 숨김
function handleConditionChange(selectElement) {
  var conditValue = selectElement.value;
  var installmentDetails = document.getElementById('installmentDetails');
  var numInstallmentsInput = document.getElementById('numInstallments');

  // 일시불을 선택해도 분할 매출 테이블을 숨기지 않도록 수정
  installmentDetails.style.display = 'block';
  if (conditValue === '분할') {
    installmentDetails.style.backgroundColor = '#f8d7da';
    numInstallmentsInput.disabled = false;
  } else {
    installmentDetails.style.backgroundColor = '';
    numInstallmentsInput.disabled = true;
    numInstallmentsInput.value = ''; // 분할이 아닐 때 입력값 초기화
  }
}

// 분할매출 금액 및 합계

// 분할매출 섹션 배경색 변경
function changeBackground(selectElement) {
  var parentDiv = selectElement.closest('#installmentDetails'); // 부모 컨테이너를 찾습니다.
  if (selectElement.value === '분할') {
    parentDiv.style.backgroundColor = '#f0f8ff'; // 분할을 선택했을 때의 배경색
  } else {
    parentDiv.style.backgroundColor = ''; // 기본 배경색
  }
}

// nonePO 분할매출 테이블
document.addEventListener('DOMContentLoaded', function () {
  var conditElement = document.getElementById('condit');
  if (conditElement) {
    conditElement.addEventListener('change', function () {
      var numInstallmentsInput = document.getElementById('numInstallments');
      // 일시불을 선택해도 분할 매출 테이블을 숨기지 않도록 수정
      numInstallmentsInput.disabled = this.value !== '분할';
      if (this.value !== '분할') {
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
    '<input type="text" class="form-control text-end price" name="installment_price[]" oninput="applyFormatNumber(this)">';
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

// sales_rate 입력 시 % 추가
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

function applyFormatNumber(input) {
  var value = input.value.replace(/,/g, ''); // 기존 콤마 제거
  if (!isNaN(value) && value !== '') {
    input.value = formatNumber(parseFloat(value).toFixed(0)); // 콤마 추가
  }
  updateInTotal(); // InTotal 업데이트
}

function updatePrice(input) {
  var value = input.value.replace(/,/g, ''); // 콤마 제거
  input.value = formatNumber(value); // 콤마 다시 추가
  updateLineTotal(input); // amt 계산 추가
}

function updateLineTotal(input) {
  var $row = $(input).closest('tr');
  var price =
    parseFloat($row.find('input[name="price[]"]').val().replace(/,/g, '')) || 0;
  var qty =
    parseFloat($row.find('input[name="qty[]"]').val().replace(/,/g, '')) || 0;
  var amt = price * qty;
  console.log('amt:', amt);
  $row.find('input[name="amt[]"]').val(formatNumber(amt.toFixed(0))); // amt 필드 업데이트

  updateTotal(); // 전체 합계 업데이트
}

function updateTotal() {
  var total = 0;
  document.querySelectorAll('input[name="amt[]"]').forEach(function (amtInput) {
    var value = parseFloat(amtInput.value.replace(/,/g, ''));
    if (!isNaN(value)) {
      total += value;
      console.log('total:', total);
    }
  });
  document.getElementById('FTotal').value = formatNumber(total.toFixed(0)); // 결과를 포맷하여 총합에 표시
}

function updateInTotal() {
  var total = 0;
  document
    .querySelectorAll('input[name="installment_price[]"]')
    .forEach(function (priceInput) {
      var value = parseFloat(priceInput.value.replace(/,/g, ''));
      if (!isNaN(value)) {
        total += value;
      }
    });
  console.log('InTotal:', total); // 디버깅을 위해 콘솔에 출력
  document.getElementById('InTotal').value = formatNumber(total.toFixed(0)); // 결과를 포맷하여 InTotal에 표시
}

function Calc(input) {
  updatePrice(input);
  updateLineTotal(input);
}

function attachEditButtonListener() {
  var editButton = document.getElementById('edit-button');
  if (editButton) {
    // 기존 이벤트 리스너 제거
    editButton.removeEventListener('click', editButtonClickHandler);
    console.log('editButtonClickHandler 제거');

    // 새로운 이벤트 리스너 추가
    editButton.addEventListener('click', editButtonClickHandler);
    console.log('editButtonClickHandler 추가');
  }
}

function editButtonClickHandler() {
  console.log('editButtonClickHandler 호출됨');

  // 선택된 발주번호 가져오기
  var selectedOrderNo = $('input[type="checkbox"].row-checkbox:checked').val();
  if (selectedOrderNo) {
    console.log('선택된 발주번호:', selectedOrderNo); // 콘솔에 선택된 발주번호 출력

    // order_new.php 페이지로 이동
    window.open('order_edit.php?order_no=' + selectedOrderNo, '_blank');
  } else {
    alert('발주를 선택해주세요.');
  }
}
// 체크박스 클릭 시 다른 체크박스 선택 해제
$(document).on('click', '.row-checkbox', function () {
  $('.row-checkbox').not(this).prop('checked', false);
});

// 전체 선택/해제 기능
$('#selectAll').on('click', function () {
  $('.row-checkbox').prop('checked', this.checked);
});

// 개별 체크박스 선택 시 전체 선택 체크박스 상태 업데이트
$(document).on('change', '.row-checkbox', function () {
  if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
    $('#selectAll').prop('checked', true);
  } else {
    $('#selectAll').prop('checked', false);
  }
});
