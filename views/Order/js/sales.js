console.log($('#salesRegisterBtn').data('bs.toggle'));
function BtnAdd_s() {
  var lastSerialNo = 0;
  var lastRow = $('#salesItemBody tr:last');
  if (lastRow.length > 0 && lastRow.find('.serial_no').val()) {
    lastSerialNo = parseInt(lastRow.find('.serial_no').val());
  }
  var newSubNo = lastSerialNo + 1; // 마지막 serial_no에서 1을 더합니다.

  let newRow = $('#salesItemRow').clone().appendTo('#salesItemBody');
  newRow.removeAttr('id'); // id 속성 제거

  newRow.find('input[type="text"], input[type="number"]').val(''); // 나머지 필드 초기화
  newRow.find('select').prop('selectedIndex', 0); // 선택 필드 초기화

  newRow.find('.serial_no').val(newSubNo); // 새로운 serial_no 설정

  lastSerialNo = newSubNo; // 전역 변수 업데이트
}
$(document).ready(function () {
  // 초기 행 설정
  if ($('#salesItemBody tr').length === 0) {
    // 테이블에 행이 없는 경우
    BtnAdd_s(); // 첫 번째 행을 추가
    updateSubNos();
  }
});
function updateSubNos() {
  $('#salesItemBody tr').each(function (index) {
    $(this)
      .find('.serial_no')
      .val(index + 1);
  });
}

$(document).ready(function () {
  GetAllTotal(); // 초기 페이지 로드 시 총합 계산

  // 동적으로 추가된 입력 필드에도 이벤트 리스너를 적용
  $('#salesItemBody').on('input', 'input[name="sales_amt[]"]', function () {
    console.log('Input field changed, recalculating total...');
    GetAllTotal();
  });
});

function GetAllTotal() {
  let sum = 0;
  let totalRate = 0;
  $('#salesItemBody')
    .find('input[name="sales_amt[]"]')
    .each(function () {
      let amtValue = $(this).val().replace(/,/g, ''); // 콤마 제거
      let amt = parseFloat(amtValue) || 0;
      if (!isNaN(amt)) {
        sum += amt;
      }
    });

  // 매출 비율 계산
  $('#salesItemBody')
    .find('input[name="sales_rate[]"]')
    .each(function () {
      let rateValue = $(this).val().replace('%', ''); // 퍼센트 제거
      let rate = parseFloat(rateValue) || 0;
      if (!isNaN(rate)) {
        totalRate += rate;
      }
    });
  // 누적 합계 도출
  $('#totalSalesAmount').text(
    '누적 매출금액: ' + formatNumber(sum.toFixed(0)) + '원'
  );
  $('#totalSalesRate').text('누적 매출비율: ' + totalRate.toFixed(2) + '%');

  // 누적 매출 비율이 100%를 초과하는 경우 경고
  if (totalRate > 100) {
    alert(
      '경고: 누적 매출 비율이 100%를 초과했습니다. 입력 금액을 다시 확인해주세요.'
    );
  }
}

//천단위 콤마 삽입
function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

//업데이트 화면에서 천단위 콤마 삽입
function applyFormatNumber(inputElement) {
  var formattedValue = formatNumber(inputElement.value.replace(/,/g, '')); // 먼저 기존 콤마를 제거
  inputElement.value = formattedValue; // 포맷된 값을 다시 입력 필드에 설정
}

// 금액 입력 변경 감지 및 처리
document.addEventListener('DOMContentLoaded', function () {
  var salesForm = document.getElementById('salesForm');
  if (salesForm) {
    salesForm.addEventListener('submit', function (event) {});
  } else {
    console.log('salesForm not found');
  }
});

document.body.addEventListener('click', function (event) {
  const deleteDbButton = event.target.closest('.delete-db-btn');
  const deleteUiButton = event.target.closest('.delete-ui-btn');

  if (deleteDbButton) {
    const serialNo = deleteDbButton.getAttribute('data-serial-no');
    const orderNo = deleteDbButton.getAttribute('data-order-no');
    if (confirm('정말 삭제하시겠습니까?')) {
      fetch('sales_process.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body:
          'action_type=delete_sales&serial_no=' +
          serialNo +
          '&order_no=' +
          orderNo,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === 'success') {
            alert('삭제 성공');
            location.reload();
          } else {
            alert('삭제 실패: ' + data.message);
          }
        })
        .catch((error) => {
          console.error('Error:', error);
          alert('처리 중 오류가 발생했습니다: ' + error.message);
        });
    }
  } else if (deleteUiButton) {
    // 화면에서만 행을 삭제
    deleteUiButton.closest('tr').remove();
  }
});
// $(document).ready(function () {
//   setupSearchHandler();
//   // $(document).on('click', '#edit-button', redirectToEdit); // 이벤트 위임을 사용하여 편집 버튼에 대한 리스너 설정
// });

//검색 핸들러

function setupSearchHandler() {
  var debounceTimer;
  $('#getWords').keyup(function () {
    clearTimeout(debounceTimer);
    var input = $(this).val().trim();
    console.log(input);
    if (input.length >= 3) {
      // 조건을 3글자 이상으로 변경
      debounceTimer = setTimeout(function () {
        // 디바운싱 적용
        $.ajax({
          url: 'searchajax_s.php', // 검색 처리를 위한 서버 측 스크립트
          type: 'POST',
          data: { input: input },
          success: function (response) {
            $('#searchResultContainer').html(response); // 검색 결과를 표시할 요소
          },
          error: function (xhr, status, error) {
            console.error('검색 에러:', status, error); // 에러 로깅
            alert('검색을 수행할 수 없습니다. 에러를 확인해주세요.');
          },
        });
      }, 300); // 300ms 후에 검색 실행
    } else {
      $('#searchResultContainer').html(''); // 입력 길이가 짧을 때 결과 비우기
    }
  });
}

$(document).on('click', '#salesRegisterBtn', function () {
  const checkboxes = $('.row-checkbox:checked');
  const orderNos = checkboxes
    .map(function () {
      return this.value;
    })
    .get()
    .join(',');

  if (orderNos) {
    const url = `sales_installment.php?order_no=${orderNos}`;
    window.open(url, '_blank');
  } else {
    alert('선택된 주문이 없습니다.');
  }
});

$(document).ready(function () {
  // 모달을 열기 위한 버튼 클릭 이벤트 핸들러
  $('#salesRegisterBtn').on('click', function () {
    var myModal = new bootstrap.Modal(document.getElementById('nonePO'));
    myModal.show();
  });
});

// 일시불 입금확인 절차
document.body.addEventListener('click', function (event) {
  if (event.target && event.target.id === 'confirmButton') {
    if (confirm('입금을 확인하시겠습니까? 이 작업은 되돌릴 수 없습니다.')) {
      sendData();
    }
  }
});

function sendData() {
  var form = document.getElementById('paymentForm');
  var formData = new FormData(form);

  fetch(form.action, {
    method: 'POST',
    body: formData,
  })
    .then((response) => response.text()) // 텍스트로 응답 받기
    .then((text) => {
      alert(text); // 서버로부터 받은 텍스트 메시지를 알림으로 표시
    })
    .catch((error) => {
      alert('네트워크 오류가 발생했습니다. 다시 시도해주세요.');
      console.error('Error:', error);
    });
}

// 매출 정보 업데이트용 체크박스 및 버튼 이벤트 처리
$(document).ready(function () {
  $('#sales_edit-button').click(function (event) {
    console.log('편집 버튼 클릭');
    event.preventDefault(); // 기본 동작 방지
    redirectToSalesEdit();
  });

  $('#sales_delete-button').click(function (event) {
    event.preventDefault(); // 기본 동작 방지
    deleteSelectedQuotes();
  });
});
function redirectToSalesEdit() {
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
        url: 'order_process.php',
        type: 'POST',
        data: { order_no: selectedOrderNos.join(',') }, // 여러 발주번호를 쉼표로 구분된 문자열로 전송
        dataType: 'json',
        success: function (response) {
          if (response.status === 'success') {
            console.log('발주 삭제 완료');
            location.reload(); // 성공적으로 삭제 후 페이지 새로고침
          } else {
            console.error('발주 삭제 실패:', response.message);
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
