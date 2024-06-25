$(document).ready(function () {
  $('body').on('click', '#addButtonId', function () {
    M_BtnAdd(); // M_BtnAdd 함수 호출 버튼의 ID가 #addButtonId라고 가정
  });

  $('body').on('click', '#updateButtonId', function () {
    TU_BtnAdd(); // TU_BtnAdd 함수 호출 버튼의 ID가 #updateButtonId라고 가정
  });
});

//행 추가
function BtnAdd() {
  console.log('BtnAdd is called');
  var newRow = $('#TRow').clone().appendTo('#TBody');
  newRow.removeAttr('id');
  newRow.find('input[type="text"], input[type="number"]').val('');
  newRow.find('select').prop('selectedIndex', 0);
  updateSubNos(); // 모든 sub_no 업데이트
  console.log('Row cloned and appended');
}

//데이터베이스에서 삭제 처리
$('.btn-delete-db').on('click', function () {
  if (confirm('정말로 DB에서 이 항목을 삭제하시겠습니까?')) {
    var row = $(this).closest('tr');
    var subNo = row.find('.sub_no').val();
    var eNo = $(this).data('e-no');
    deletefacilityRow(subNo, eNo);
    row.remove();
    updateSubNos();
  }
});

//모달 행 추가
//모달 행 추가

//작업 Update 모달에서 행 추가
function TU_BtnAdd() {
  console.log('TU_BtnAdd is called');
  var newRow = $('#TU_Row').clone().appendTo('#TU_Body'); // 수정된 셀렉터
  newRow.removeAttr('id');

  // 행의 개수를 기반으로 t_no 값을 업데이트
  var rowCount = $('#TU_Body tr').length; // 현재 행의 개수를 가져옴
  console.log('Row count before setting t_no:', rowCount);
  newRow.find('.t_no').val(rowCount); // t_no 입력란에 행의 개수를 설정
  console.log('New t_no set to:', rowCount);

  // 기타 필드 초기화 제외
  newRow.find('input[type="text"], input[type="number"]').not('.t_no').val('');
  newRow.find('select').prop('selectedIndex', 0);
  console.log('Row cloned and appended');
}

// 모든 sub_no 업데이트
function updateSubNos() {
  $('#TBody .sub_no').each(function (index) {
    $(this).val(index + 1); // sub_no를 1부터 시작하여 재정렬
  });
}
// 모달 내의 행 업데이트
$('.modal-body #T_Body tr').each(function (index) {
  $(this)
    .find('.sub_no')
    .val(index + 1);
});
//DB에 특정 행을 삭제
function deletefacilityRow(subNo, eNo) {
  console.log('Sending delete request for sub_no:', subNo, 'e_no:', eNo);
  $.ajax({
    url: 'facility_process.php',
    type: 'POST',
    data: { action: 'delete', sub_no: subNo, e_no: eNo },
    dataType: 'json', // 서버로부터 JSON 응답을 기대
    success: function (response) {
      console.log('Response:', response);
      if (response.success) {
        alert('성공적으로 삭제되었습니다.');
        location.reload();
      } else {
        alert('삭제 처리 중 오류가 발생했습니다.');
      }
    },
    error: function (xhr, status, error) {
      console.error('Error:', xhr.responseText);
      alert('삭제 처리 중 오류가 발생했습니다.');
    },
  });
}

function IcoDel(v) {
  // 사용자에게 삭제를 확인받음
  if (confirm('이 행을 삭제하시겠습니까?')) {
    var row = $(v).closest('tr');
    var subNo = row.find('.sub_no').val(); // 삭제할 행의 sub_no를 가져옴
    deleteEquipment(subNo);
  }
}

// 모달에셔 설비 추가 를 db저장 성공 실패 메세지 관리
$(document).ready(function () {
  $('#modalForm').on('submit', function (e) {
    e.preventDefault(); // 폼 기본 제출 방지
    var formData = $(this).serialize(); // 폼 데이터 직렬화

    $.ajax({
      type: 'POST',
      url: 'facility_process.php',
      data: formData,
      success: function (response) {
        alert('저장이 완료되었습니다.');
        $('#myModal').modal('hide'); // 모달 숨기기
        location.reload(); // 페이지 새로고침
      },
      error: function () {
        alert('데이터 처리 중 오류가 발생했습니다.');
      },
    });
  });
});
// 모달에셔 작업 추가 를 db저장 성공 실패 메세지 관리
$('#modalForm').on('submit', function (e) {
  e.preventDefault();
  var formData = $(this).serialize();
  console.log('Form data: ', formData); // 폼 데이터 로깅

  $.ajax({
    type: 'POST',
    url: 'task_process.php',
    data: formData,
    dataType: 'json',
    success: function (response) {
      if (response.error) {
        alert('오류가 발생했습니다: ' + response.error);
      } else {
        alert('저장이 완료되었습니다.');
        $('#myModal').modal('hide');
        location.reload();
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error:', error);
      alert('오류가 발생했습니다: ' + error);
    },
  });
});
//모달의 위치 조정
$('#staticBackdrop').on('shown.bs.modal', function () {
  var $modal = $(this),
    $dialog = $modal.find('.modal-dialog');
  var calcTop = Math.max(0, ($(window).height() - $dialog.outerHeight()) / 3); // 상단 여백을 줄임
  $dialog.css('margin-top', calcTop);
});

//모달내 텍스트 자동 리사이즈
document.querySelectorAll('textarea').forEach((textarea) => {
  textarea.addEventListener('input', autoResize, false);

  function autoResize() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
  }
});

//기존 모달에서 항목추가를 위해 새모달 호출
$(document).ready(function () {
  $('#taskPartSelect').change(function () {
    if ($(this).val() == '항목추가') {
      $('#addOptionModal').modal('show'); // 새 항목 추가 모달을 열기
    }
  });
});

// 작업 항목 불러오기
function addNewTaskPart() {
  var newTaskPart = $('#newTaskPart').val();
  $.ajax({
    url: 'save_add_item.php',
    type: 'POST',
    data: { add_task_part: true, taskpart: newTaskPart },
    success: function (response) {
      console.log(response); // 응답 로깅
      if (response.error) {
        alert('오류가 발생했습니다: ' + response.error);
      } else {
        $('#taskPartSelect').append(
          $('<option>', {
            value: response.tp_no,
            text: response.taskpart,
          })
        );
        $('#taskPartSelect').val(response.tp_no);
        alert('저장이 완료되었습니다.');
        $('#addOptionModal').modal('hide');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error:', error);
      alert('오류가 발생했습니다: ' + error);
    },
  });
}

//작업 추가 모달
function openModalWithSeriNo(seriNo) {
  $('#modalForm input[name="seri_no"]').val(seriNo);
  $('#myModal').modal('show');
}
//task_add_modal을 호출하는 함수
$(document).ready(function () {
  $('#staticBackdrop').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // 모달을 트리거하는 버튼
    var seriNo = button.data('seri-no'); // 버튼의 data-seri-no 속성 값

    // 모달 내 hidden input 필드에 seri_no 설정
    var modal = $(this);
    modal.find('#modalSeriNo').val(seriNo);
  });
});
// 행의 개수를 기반으로 t_no 값을 업데이트
var rowCount = $('#T_Body tr').length; // 현재 행의 개수를 가져옴
console.log('Row count before setting t_no:', rowCount);
newRow.find('.t_no').val(rowCount); // t_no 입력란에 행의 개수를 설정
console.log('New t_no set to:', rowCount);

newRow.find('input[type="text"], input[type="number"]').val('');
newRow.find('select').prop('selectedIndex', 0);
updateSubNos(); // 모든 sub_no 업데이트
console.log('Row cloned and appended');
