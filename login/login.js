document.addEventListener('DOMContentLoaded', () => {
  const id = document.querySelector('#id');
  const pw = document.querySelector('#pw');
  const btn = document.querySelector('#login_btn');
  btn.addEventListener('click', (e) => {
    e.preventDefault();

    if (id.value == '') {
      alert('아이디를 입력해주세요');
      id.focus();
      return false;
    }
    if (pw.value == '') {
      alert('비밀번호를 입력해주세요');
      pw.focus();
      return false;
    }
    document.login_form.submit();
  });
});

//모달의 위치 조정
$('#staticBackdrop').on('shown.bs.modal', function () {
  var $modal = $(this),
    $dialog = $modal.find('.modal-dialog');
  var calcTop = Math.max(0, ($(window).height() - $dialog.outerHeight()) / 3); // 상단 여백을 줄임
  $dialog.css('margin-top', calcTop);
});
