function initializePage() {
  $('#addButton').on('click', function () {
    console.log('addButton clicked');
    // BtnAdd_o();
  });
  // 다른 필요한 초기화 코드
}

$(document).ready(function () {
  initializePage();
});

$(document).ready(function () {
  // 동적으로 생성된 요소에 대한 이벤트 핸들러 바인딩
  $('body').on('click', '#addButton', function () {
    // BtnAdd_o();
  });

  // 행 삭제 버튼에 대한 이벤트 위임
  $('#tableBody').on('click', '.deleteButton', function () {
    BtnDel_o(this);
  });
});

$('input[name="price[]"], input[name="qty[]"]').on('input', function () {
  updatePrice(this); // 콤마 추가 및 amt 계산
});

$(document).ready(function () {
  GetTotal(); // 페이지 로드 시 총합 계산
  // 다른 초기화 코드
});

function BtnAdd_o() {
  var lastSubNo = 0; // 기본값 설정
  var lastRow = $('#orderItemBody tr:last');
  if (lastRow.length > 0 && lastRow.find('.o_no').val()) {
    lastSubNo = parseInt(lastRow.find('.o_no').val());
  }
  var newSubNo = lastSubNo + 1;

  let newRow = $('#orderItemRow').clone().appendTo('#orderItemBody');
  newRow.removeAttr('id'); // id 속성 제거

  // 모든 입력 필드 초기화
  newRow.find('input[type="text"], input[type="number"]').val('');
  newRow.find('select').prop('selectedIndex', 0);

  // 새로운 행의 o_no 입력 필드에 새로운 o_no 값을 설정
  newRow.find('.o_no').val(newSubNo);
}

function BtnDel_o(v) {
  $(v).closest('tr').remove(); // 행 삭제
  GetTotal(); // 전체 합계 다시 계산
}

// 행 번호 재설정 (필요한 경우)
$('#orderItemBody')
  .find('tr')
  .each(function (index) {
    $(this)
      .find('.row-number')
      .text(index + 1);
  });

// 단가 * 수량 = 합계
function Calc(v) {
  let $row = $(v).closest('tr');
  let price = parseFloat(
    $row.find('input[name="price[]"]').val().replace(/,/g, '')
  );
  let qty = parseFloat(
    $row.find('input[name="qty[]"]').val().replace(/,/g, '')
  );

  let amt = price * qty;
  if (!isNaN(amt)) {
    $row.find('input[name="amt[]"]').val(formatNumber(amt.toFixed(0))); // 콤마 추가
  }

  GetTotal();
}
// 전체 합계 계산
function GetTotal() {
  let sum = 0;
  $('#orderItemBody')
    .find('input[name="amt[]"]')
    .each(function () {
      let amtValue = $(this).val().replace(/,/g, ''); // 콤마 제거
      let amt = parseFloat(amtValue) || 0;
      if (!isNaN(amt)) {
        sum += amt;
      }
    });
  $('#FTotal').val(formatNumber(sum.toFixed(0))); // 콤마 추가하여 전체 합계 업데이트
}
// 숫자를 콤마로 분리하는 함수
function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
// 입력 필드에서 숫자를 입력할 때 콤마 자동 추가 및 amt 계산
$('input[name="price[]"], input[name="qty[]"]').on('input', function () {
  updatePrice(this); // 콤마 추가 및 amt 계산
});

//업데이트 화면에서 천단위 콤바 삽입
function applyFormatNumber(inputElement) {
  var formattedValue = formatNumber(inputElement.value.replace(/,/g, '')); // 먼저 기존 콤마를 제거
  inputElement.value = formattedValue; // 포맷된 값을 다시 입력 필드에 설정
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
  $row.find('input[name="amt[]"]').val(formatNumber(amt.toFixed(0))); // amt 필드 업데이트

  updateTotal(); // 전체 합계 업데이트
}
function updateTotal() {
  var total = 0;
  document.querySelectorAll('.amt').forEach(function (amtInput) {
    var value = parseFloat(amtInput.value.replace(/,/g, '')); // 콤마를 제거하고 숫자로 변환
    if (!isNaN(value)) {
      total += value;
    }
  });
  document.getElementById('FTotal').value = formatNumber(total.toFixed(0)); // 결과를 포맷하여 총합에 표시
}

// 발주 정보 업데이트용 체크박스 및 버튼 이벤트 처리
$(document).ready(function () {
  $('#edit-button').click(function (event) {
    event.preventDefault(); // 기본 동작 방지
    redirectToEdit();
  });

  $('#delete-button').click(function (event) {
    event.preventDefault(); // 기본 동작 방지
    deleteSelectedQuotes();
  });
});
function redirectToEdit() {
  var selectedOrderNo = $('input[type="checkbox"].row-checkbox:checked').val();
  console.log('선택된 발주번호:', selectedOrderNo); // 콘솔에 선택된 발주번호 출력

  if (selectedOrderNo) {
    window.open('order_update.php?order_no=' + selectedOrderNo, '_blank');
  } else {
    alert('발주를 선택해주세요.');
  }
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
//기간별 조회 화면
//검색어와 체크박스 선택후 액션
$(document).ready(function () {
  setupSearchHandler();
  // $(document).on('click', '#edit-button', redirectToEdit); // 이벤트 위임을 사용하여 편집 버튼에 대한 리스너 설정
});

function handleYearChange(selectedYear) {
  console.log('선택된 연도:', selectedYear);
  // 필요한 로직 추가, 예를 들어 AJAX 호출 등
  fetchQuotesByPeriod(selectedYear);
}
function fetchQuotesByPeriod(period) {
  $.ajax({
    url: 'fetch_order.php',
    type: 'GET',
    data: { period: period },
    success: function (data) {
      $('#order-table-body').html(data);
      attachEditButtonListener(); // 이벤트 리스너 재설정
    },
    error: function () {
      alert('데이터를 불러오는 데 실패했습니다.');
    },
  });
}

function setPeriod(period) {
  fetchQuotesByPeriod(period);
}

$('#oneYearBtn').click(function () {
  fetchQuotesByPeriod('1year');
});

$('#threeYearsBtn').click(function () {
  fetchQuotesByPeriod('3years');
});

$('#yearSelect').change(function () {
  var selectedYear = $(this).val();
  fetchQuotesByPeriod(selectedYear);
});

function setupSearchHandler() {
  $('#getWords').keyup(function () {
    var input = $(this).val().trim();
    console.log(input);
    if (input.length > 1) {
      // 최소 3글자 이상 입력했을 때 검색 실행
      $.ajax({
        url: 'searchajax_o.php', // 검색 처리를 위한 서버 측 스크립트
        type: 'POST',
        data: { input: input },
        success: function (response) {
          $('#searchResultContainer').html(response); // 검색 결과를 표시할 요소
        },
        error: function () {
          alert('검색을 수행할 수 없습니다.');
        },
      });
    } else {
      $('#searchResultContainer').html(''); // 입력 길이가 짧을 때 결과 비우기
    }
  });
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

function addInstallmentRow(index) {
  var tbody = document
    .getElementById('installmentTable')
    .getElementsByTagName('tbody')[0];
  var row = tbody.insertRow();
  var cell1 = row.insertCell(0);
  var cell2 = row.insertCell(1);
  var cell3 = row.insertCell(2);
  var cell4 = row.insertCell(3);
  var cell5 = row.insertCell(4);
  var cell6 = row.insertCell(5);

  cell1.innerHTML = index || tbody.rows.length + 1;
  cell2.innerHTML = '<input type="text" name="price[]">';
  cell3.innerHTML = '<input type="text" name="sales_rate[]" placeholder="%">';
  cell4.innerHTML = '<input type="date" name="sales_date[]">';
  cell6.innerHTML = '<input type="text" name="sales_remark[]">';
  cell5.innerHTML =
    '<button type="button" onclick="removeInstallmentRow(this)">삭제</button>';
}

function removeInstallmentRow(button) {
  var row = button.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

function resetInstallments() {
  document.getElementById('numInstallments').value = ''; // 분할 횟수 입력란 초기화
  var tbody = document
    .getElementById('installmentTable')
    .getElementsByTagName('tbody')[0];
  tbody.innerHTML = ''; // 분할 테이블의 행을 모두 제거
}

//상세정보 저장후 분할정보 호출
document.addEventListener('DOMContentLoaded', function () {
  var detailForm = document.getElementById('detailForm');
  if (detailForm) {
    detailForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var formData = new FormData(detailForm);
      fetch('update_process.php', {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (
            confirm('상세 정보가 저장되었습니다. 분할 정보를 입력하시겠습니까?')
          ) {
            document.getElementById('partitionModal').style.display = 'block';
          }
        });
    });
  }

  var partitionForm = document.getElementById('partitionForm');
  if (partitionForm) {
    partitionForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var partitionData = new FormData(partitionForm);
      fetch('update_partition_process.php', {
        method: 'POST',
        body: partitionData,
      })
        .then((response) => response.json())
        .then((data) => {
          alert('분할 정보 저장 완료.');
          document.getElementById('partitionModal').style.display = 'none';
        });
    });
  }
});
