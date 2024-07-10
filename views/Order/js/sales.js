//체크박스 선택시 이전 정보 리셋
$(document).on('click', '.row-checkbox', function () {
  $('.row-checkbox').not(this).prop('checked', false);
});

$(document).ready(function () {
  // 페이지 로드 시 FTotal 계산 및 출력
  calculateFTotal();
});

function calculateFTotal() {
  let totalAmt = 0;

  // 각 행의 amt 값을 더함
  $('#orderItemBody tr').each(function () {
    let amt = $(this).find('td').eq(10).text().replace(/,/g, ''); // 11번째 열의 amt 값 가져오기 (콤마 제거)
    amt = parseFloat(amt) || 0; // 숫자로 변환
    totalAmt += amt;
  });

  // FTotal 출력
  $('#FTotal').text(totalAmt.toLocaleString() + ' 원');
}
//매출등록 위한 새창 열기
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
    window.open('order_edit.php?order_no=' + selectedOrderNo, '_blank');
  } else {
    alert('발주를 선택해주세요.');
  }
}
