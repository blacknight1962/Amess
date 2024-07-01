$(document).ready(function () {
  initializeEventHandlers();
  initializePage();
  GetTotal(); // 페이지 로드 시 총합 계산
  setupSearchHandler(); // 검색 핸들러 설정
});

function initializePage() {
  $('#addButton').on('click', function () {
    console.log('addButton clicked');
    // BtnAdd_o();
  });
  // 다른 필요한 초기화 코드
}

function initializeEventHandlers() {
  console.log('Event handlers initialized');
  $('#oneYearBtn').click(() => setPeriod('1year'));
  $('#threeYearsBtn').click(() => setPeriod('3years'));
  $('#yearSelect').change((event) => handleYearChange(event.target.value));
  $('#edit-button').click(function (event) {
    event.preventDefault(); // 기본 동작 방지
    redirectToEdit();
  });
  $('#delete-button').click(function (event) {
    event.preventDefault(); // 기본 동작 방지
    deleteSelectedQuotes();
  });
  $('input[name="price[]"], input[name="qty[]"]').on('input', function () {
    updatePrice(this); // 콤마 추가 및 amt 계산
  });
  $('body').on('click', '#addButton', function () {
    // BtnAdd_o();
  });
  $('#tableBody').on('click', '.deleteButton', function () {
    BtnDel_o(this);
  });
}

function setupSearchHandler() {
  var searchInput = $('#getWords');
  if (searchInput.length > 0) {
    console.log('Search input found');
    searchInput.keyup(function () {
      var input = $(this).val().trim();
      console.log('Input value:', input);
      if (input.length > 1) {
        // 최소 2글자 이상 입력했을 때 검색 실행
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
  } else {
    console.log('Search input not found');
  }
}

function handleYearChange(selectedYear) {
  console.log('선택된 연도:', selectedYear);
  fetchQuotesByPeriod(selectedYear);
}

function setPeriod(period) {
  fetchQuotesByPeriod(period);
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

function handleYearChange(selectedYear) {
  console.log('선택된 연도:', selectedYear);
  fetchQuotesByPeriod(selectedYear);
}

function setPeriod(period) {
  fetchQuotesByPeriod(period);
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

function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
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
    var value = parseFloat(amtInput.value.replace(/,/g, ''));
    if (!isNaN(value)) {
      total += value;
    }
  });
  document.getElementById('FTotal').value = formatNumber(total.toFixed(0)); // 결과를 포맷하여 총합에 표시
}

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

function attachEditButtonListener() {
  // 기존 이벤트 리스너 제거
  document
    .getElementById('edit-button')
    .removeEventListener('click', editButtonClickHandler);

  // 새로운 이벤트 리스너 추가
  document
    .getElementById('edit-button')
    .addEventListener('click', editButtonClickHandler);
}

function editButtonClickHandler() {
  // 페이지 로드 시 attachEditButtonListener 함수를 호출하여 이벤트 리스너를 추가합니다.
  document.addEventListener('DOMContentLoaded', function () {
    attachEditButtonListener();
  });
}
