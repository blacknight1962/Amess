function deleteSelectedManufact(orderNo) {
  if (orderNo && confirm('선택한 발주를 삭제하시겠습니까?')) {
    console.log('Deleting order:', orderNo);
    $.ajax({
      url: 'update_process.php',
      type: 'POST',
      data: {
        action_type: 'delete_manufact', // action_type 추가
        order_no: orderNo, // 단일 발주번호 전송
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
}
