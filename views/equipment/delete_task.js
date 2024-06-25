$(document).on('click', '.deleteButton', function () {
  var row = $(this).closest('tr');
  var seri_no = row.find('.seri_no').val(); // 가정: seri_no가 input hidden 필드에 저장되어 있음
  var t_no = row.find('.t_no').val(); // t_no 값을 가져옵니다.
  console.log('seri_no:', seri_no, 't_no:', t_no); // 콘솔에서 값 확인

  if (confirm('이 설비 정보를 삭제하시겠습니까?')) {
    $.ajax({
      url: 'delete_task.php',
      type: 'POST',
      data: {
        action: 'delete',
        t_no: t_no,
        seri_no: seri_no,
      },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          console.log('Task deleted successfully');
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
