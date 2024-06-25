//행 삭제
$(document).on('click', '.BtnDelF', function () {
  var row = $(this).closest('tr');
  var subNo = row.find('.sub_no').val();
  var eNo = row.data('e-no'); // jQuery data() 메소드 사용

  console.log('subNo:', subNo, 'eNo:', eNo); // 콘솔에서 값 확인

  if (confirm('이 설비 정보를 삭제하시겠습니까?')) {
    $.ajax({
      url: 'facility_process.php',
      type: 'POST',
      data: {
        action: 'delete',
        sub_no: subNo,
        e_no: eNo,
      },
      success: function (response) {
        if (response.success) {
          console.log('Facility row deleted successfully');
          row.remove(); // 화면에서 행 삭제
          updateSubNos(); // 번호 재정렬
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error('Error deleting facility row:', error);
        if (xhr.responseText.startsWith('<')) {
          console.error(
            'Received HTML response from server. Please check server-side logs for more details.'
          );
          alert('서버 오류가 발생했습니다. 관리자에게 문의하세요.');
        }
      },
    });
  }
});

// 행 추가
$('#addButton').on('click', function () {
  F_BtnAdd();
});

function F_BtnAdd() {
  var newRow = $('#T_Row').clone().appendTo('#T_Body');
  newRow.removeAttr('id');
  newRow.find('input[type="text"], input[type="number"]').val('');
  newRow.find('select').prop('selectedIndex', 0);
  var rowCount = $('#TBody tr').length;
  newRow.find('.sub_no').val(rowCount); // sub_no 설정
  updateSubNos();
  console.log('Row cloned and appended');
}
function updateSubNos() {
  $('#T_Body tr').each(function (index) {
    $(this)
      .find('.sub_no')
      .val(index + 1);
  });
}
// 행 추가
$('#addBtton').on('click', function () {});
function FT_BtnAdd() {
  var newRow = $('#FT_Row').clone().appendTo('#FT_Body');
  newRow.removeAttr('id');
  newRow.find('input[type="text"], input[type="number"]').val('');
  newRow.find('select').prop('selectedIndex', 0);
  var rowCount = $('#FT_Body tr').length;
  newRow.find('.sub_no').val(rowCount); // sub_no 설정
  updateSubNos();
  console.log('Row cloned and appended');
}
function updateSubNos() {
  $('#FT_Body tr').each(function (index) {
    $(this)
      .find('.sub_no')
      .val(index + 1);
  });
}
function updateSubNos() {
  $('#T_Body tr').each(function (index) {
    $(this)
      .find('.sub_no')
      .val(index + 1);
  });
}
function BtnDelM(button) {
  // 버튼이 속한 행을 찾아서 삭제
  $(button).closest('tr').remove();

  // 행 번호 업데이트
  updateSubNos();
}
