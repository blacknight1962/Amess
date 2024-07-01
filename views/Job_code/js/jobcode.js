function deleteAllQuotes(seri_no) {
  if (confirm('정말로 이 그룹번호에 대한 모든 데이터를 삭제하시겠습니까?')) {
    console.log(seri_no);
    $.ajax({
      url: 'process_jobcode.php',
      type: 'POST',
      data: { seri_no: seri_no },
      dataType: 'json',
      success: function (response) {
        if (response.status === 'success') {
          alert(response.message); // 성공 메시지 표시
          location.reload(); // 페이지 새로고침
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
