function GetPrint() {
  /*For Print*/
  window.print();
}
$(document).ready(function () {
  // 이벤트 위임을 사용하여 모든 현재 및 미래의 'price' 및 'qty' 입력 필드에 대해 이벤트 리스너 설정
  $('#TBody').on(
    'input',
    'input[name="price[]"], input[name="qty[]"]',
    function () {
      updatePrice(this); // 콤마 추가 및 amt 계산
    }
  );
});
function BtnAdd() {
  var lastSubNo = parseInt($('#TBody tr:last').find('.sub_no').val()) || 0;
  var newSubNo = lastSubNo + 1;
  // console.log('Last sub_no: ' + lastSubNo);
  // console.log('New sub_no: ' + newSubNo);

  let newRow = $('#TRow').clone().appendTo('#TBody');
  newRow.removeAttr('id'); // id 속성 제거

  // 모든 입력 필드 초기화
  newRow.find('input[type="text"], input[type="number"]').val('');
  newRow.find('select').prop('selectedIndex', 0);

  // 새로운 행의 sub_no 입력 필드에 새로운 sub_no 값을 설정
  newRow.find('.sub_no').val(newSubNo);
}

// 페이지 로드 시 첫 번째 행 자동 추가
$(document).ready(function () {
  // 페이지 로드 시 첫 번째 행을 추가하기 전에 기존 행이 있는지 확인
  if ($('#TBody tr').length === 0) {
    BtnAdd(); // 첫 번째 행을 추가
  }
});

// 행 삭제
function BtnDel(v) {
  $(v).closest('tr').remove(); // 행 삭제
  GetTotal(); // 전체 합계 다시 계산

  // 행 번호 재설정 (필요한 경우)
  $('#TBody')
    .find('tr')
    .each(function (index) {
      $(this)
        .find('.row-number')
        .text(index + 1);
    });
}
function BtnDel(v) {
  var row = $(v).closest('tr');
  var amtValue = parseFloat(
    row.find('input[name="amt[]"]').val().replace(/,/g, '') || 0
  );

  // 행 삭제 전에 해당 행의 amt 값을 전체 합계에서 빼기
  var currentTotal = parseFloat($('#FTotal').val().replace(/,/g, '') || 0);
  var newTotal = currentTotal - amtValue;
  $('#FTotal').val(formatNumber(newTotal.toFixed(0))); // 새로운 합계 업데이트

  row.remove(); // 행 삭제
}

//**edit_quot.php 에서 1개 행을 DB에서 직접 삭제하는데 사용하는 함수 */
function IcoDel(v) {
  // 사용자에게 삭제를 확인받음
  if (confirm('이 행을 삭제하시겠습니까?')) {
    var row = $(v).closest('tr');
    var subNo = row.find('.sub_no').val(); // 삭제할 행의 sub_no를 가져옴

    // AJAX 요청을 통해 서버에 행 삭제 요청
    $.ajax({
      url: 'quot1_process.php', // 서버의 삭제 스크립트 경로
      type: 'POST',
      data: { sub_no: subNo }, // 서버에 전달할 데이터
      success: function (response) {
        console.log('서버에서 행 삭제 성공:', response);
        var amtValue = parseFloat(
          row.find('input[name="amt[]"]').val().replace(/,/g, '') || 0
        );

        // 행 삭제 전에 해당 행의 amt 값을 전체 합계에서 빼기
        var currentTotal = parseFloat(
          $('#FTotal').val().replace(/,/g, '') || 0
        );
        var newTotal = currentTotal - amtValue;
        $('#FTotal').val(formatNumber(newTotal.toFixed(0))); // 새로운 합계 업데이트

        row.remove(); // 화면에서 행 삭제

        // 성공적으로 삭제되었다면, quote_index.php로 리다이렉트
        if (response.status === 'success') {
          window.location.href = 'quote_index.php'; // 리다이렉트 경로 확인 필요
        }
      },
      error: function (xhr, status, error) {
        // 서버에서의 삭제 실패 응답 처리
        console.error('서버에서 행 삭제 실패:', error);
      },
    });
  } else {
    // 사용자가 삭제를 취소한 경우
    console.log('행 삭제가 취소되었습니다.');
  }
}
//견적관리에서 직접 견적전체를 삭제 요청시
function deleteSelectedQuotes() {
  const selectedQuotes = getSelectedRows(); // 선택된 견적 번호 가져오기
  if (selectedQuotes.length === 0) {
    alert('삭제할 항목을 선택하세요.');
    return;
  }
  selectedQuotes.forEach((quoteNo) => {
    deleteAllQuotes(quoteNo); // 각 견적 번호에 대해 삭제 함수 호출
  });
}
function deleteAllQuotes(quoteNo) {
  if (confirm('정말로 이 견적번호에 대한 모든 데이터를 삭제하시겠습니까?')) {
    $.ajax({
      url: 'quot1_process.php',
      type: 'POST',
      data: { quote_no: quoteNo },
      dataType: 'json',
      success: function (response) {
        if (response.status === 'success') {
          alert(response.message); // 성공 메시지 표시
        } else {
          alert('삭제 실패: ' + response.message); // 실패 메시지 표시
        }
      },
      error: function (xhr, status, error) {
        // 요청이 실패했을 때 실행될 코드
        console.error('Error:', status, error);
        console.log('Server response:', xhr.responseText);
      },
    });
  }
}
//천단위 콤마 삽입을 위한 함수
function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// 천단위 콤마 삽입
function Calc(v) {
  let $row = $(v).closest('tr');
  let price = parseFloat(
    $row.find('input[name="price[]"]').val().replace(/,/g, '')
  ); // 콤마 제거 후 숫자 변환
  let qty = parseFloat(
    $row.find('input[name="qty[]"]').val().replace(/,/g, '')
  ); // 콤마 제거 후 숫자 변환

  let amt = price * qty;
  if (!isNaN(amt)) {
    $row.find('input[name="amt[]"]').val(formatNumber(amt.toFixed(0))); // 콤마 추가
  }

  GetTotal();
}

function GetTotal() {
  let sum = 0;
  $('#TBody')
    .find('input[name="amt[]"]')
    .each(function () {
      let amt = parseFloat($(this).val().replace(/,/g, '') || 0);
      if (!isNaN(amt)) {
        sum += amt;
      }
    });
  $('#FTotal').val(formatNumber(sum.toFixed(0))); // 콤마 추가하여 전체 합계 업데이트
}
// 입력 필드에서 숫자를 입력할 때 콤마 자동 추가
$('input[name="price[]"], input[name="qty[]"]').on('input', function () {
  var input = this;
  var value = $(this).val().replace(/,/g, '');
  $(this).val(formatNumber(value));

  var row = $(this).closest('tr');
  var price = parseFloat(
    row.find('input[name="price[]"]').val().replace(/,/g, '') || 0
  );
  var qty = parseFloat(
    row.find('input[name="qty[]"]').val().replace(/,/g, '') || 0
  );
  var amt = price * qty;
  row.find('input[name="amt[]"]').val(formatNumber(amt.toFixed(0)));

  GetTotal();
});

function initializeQuoteInfo() {
  // 새 견적 정보 초기화
  let $quote_info = initializeQuoteInfo();
  return $quote_info;
}

function saveQuoteInfo($data) {
  // 데이터베이스에 견적 정보 저장
  saveQuoteInfo($data);
}
//<!-- HTML 폼 및 기타 사용자 인터페이스 -->

// 입력 필드에서 숫자를 입력할 때 콤마 자동 추가 및 amt 계산
$('input[name="price[]"], input[name="qty[]"]').on('input', function () {
  updatePrice(this); // 콤마 추가 및 amt 계산
});

function updatePrice(input) {
  var value = input.value.replace(/,/g, ''); // 콤마 제거
  input.value = formatNumber(value); // 콤마 다시 추가
  updateLineTotal(input); // amt 계산
}

function updateLineTotal(input) {
  var row = input.closest('tr');
  var price =
    parseFloat(row.querySelector('.price').value.replace(/,/g, '')) || 0;
  var qty = parseFloat(row.querySelector('.qty').value.replace(/,/g, '')) || 0;
  var amt = price * qty;
  row.querySelector('.amt').value = formatNumber(amt.toFixed(0)); // amt 필드 업데이트

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

//검색어와 체크박스 선택후 액션
$(document).ready(function () {
  initializeEditButton();
  setupCheckboxHandlers();
  setupSearchHandler();
  $(document).on('click', '#edit-button', redirectToEdit); // 이벤트 위임을 사용하여 편집 버튼에 대한 리스너 설정
});

function handleYearChange(selectedYear) {
  console.log('선택된 연도:', selectedYear);
  // 필요한 로직 추가, 예를 들어 AJAX 호출 등
  fetchQuotesByPeriod(selectedYear);
}
//여기 부터 수정안 함
function initializeEditButton() {
  $('#edit-button').prop('disabled', true);
}

function setupCheckboxHandlers() {
  $('.row-checkbox').change(function () {
    $('#edit-button').prop('disabled', !$('.row-checkbox:checked').length);
  });
}

function setupSearchHandler() {
  $('#getWords').keyup(function () {
    var input = $(this).val();
    if (input) {
      $.ajax({
        method: 'POST',
        url: 'searchajax_q.php',
        data: { input: input },
        success: function (response) {
          $('#searchResultContainer').html(response);
        },
      });
    } else {
      $('#searchResultContainer').html(''); // 검색어가 없을 때 결과 비우기
    }
  });
}

function getSelectedRows() {
  return $('.row-checkbox:checked')
    .map(function () {
      return $(this).val();
    })
    .get();
}
//편집
function redirectToEdit() {
  console.log('편집 버튼 클릭됨');
  var selectedRows = getSelectedRows();
  if (selectedRows.length === 0) {
    alert('편집할 항목을 선택하세요.');
    return;
  }
  window.location.href = 'edit_quot.php?quote_no=' + selectedRows[0];
}
//기간 선택에 따른 데이터 가져오기
function setPeriod(period) {
  fetchQuotesByPeriod(period);
}

function fetchQuotesByPeriod(period) {
  $.ajax({
    url: 'fetch_quotes.php',
    type: 'GET',
    data: { period: period },
    success: function (data) {
      $('#quoteTableBody').html(data);
      attachEditButtonListener(); // 이벤트 리스너 재설정
    },
    error: function () {
      alert('데이터를 불러오는 데 실패했습니다.');
    },
  });
}
$(document).on('click', '#edit-button', redirectToEdit);
function attachEditButtonListener() {
  $('#edit-button').on('click', redirectToEdit);
}
// 기간 선택자 이벤트 핸들러
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
