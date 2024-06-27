$(document).ready(function () {
  // 이벤트 위임을 사용하여 모든 현재 및 미래의 textarea에 대해 이벤트 리스너를 설정
  $(document).on('input', 'textarea', function () {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
  });
});

function M_BtnAdd() {
  var newRow = $('#TU_Row').clone().appendTo('#TU_Body');
  newRow.removeAttr('id');
  newRow.find('input[type="text"], input[type="number"], textarea').val('');
  newRow.find('select').prop('selectedIndex', 0);
  var rowCount = $('#TU_Body tr').length;
  newRow.find('.t_no').val(rowCount);
  updateSubNos();
  console.log('Row cloned and appended');

  // 세션 데이터를 새 행에 설정
  newRow.find('.date_task').val(sessionDate);
  newRow.find('.task_person').val(sessionUsername);
}

function updateSubNos() {
  $('#TU_Body tr').each(function (index) {
    $(this)
      .find('.t_no')
      .val(index + 1);
  });
}
// 작업 상세정보 입력 행 추가

function TB_BtnAdd() {
  var newRow = $('#TB_Row').clone().appendTo('#TB_Body');
  newRow.removeAttr('id');
  newRow.find('input[type="text"], input[type="number"], textarea').val('');
  newRow.find('select').prop('selectedIndex', 0);
  var rowCount = $('#TB_Body tr').length;
  newRow.find('.t_no').val(rowCount); // sub_no 설정
  updateSubNos();
  console.log('Row cloned and appended');
  // 세션 데이터를 새 행에 설정
  newRow.find('.date_task').val(sessionDate);
  newRow.find('.task_person').val(sessionUsername);
}

function updateSubNos() {
  $('#TB_Body tr').each(function (index) {
    $(this)
      .find('.t_no')
      .val(index + 1);
  });
}

//작업 상세정보 행 삭제
$(document).on('click', '.BtnDelTB', function () {
  var row = $(this).closest('tr');
  var seri_no = row.find('.seri_no').val(); // 가정: seri_no가 input hidden 필드에 저장되어 있음
  var t_no = row.find('.t_no').val(); // t_no 값을 가져옵니다.
  console.log('seri_no:', seri_no, 't_no:', t_no); // 콘솔에서 값 확인

  if (confirm('이 작업 정보를 삭제하시겠습니까?')) {
    $.ajax({
      url: 'task_process.php',
      type: 'POST',
      data: { action: 'delete', t_no: '2', seri_no: 'A10-91' },
      dataType: 'json', // 이 옵션은 응답을 JSON으로 파싱하도록 지정합니다.
      success: function (response) {
        if (response.success) {
          console.log('Task deleted successfully');
        } else {
          console.error('Error deleting task:', response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error('Error deleting task row:', error);
      },
    });
  }
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

//작업 추가 모달 호출
var addTaskModal = document.getElementById('addTaskModal');

addTaskModal.addEventListener('show.bs.modal', function (event) {
  console.log('모달이 표시됨');
  var button = event.relatedTarget; // 모달을 트리거한 버튼
  console.log('버튼:', button);
  var seriNo = button.getAttribute('data-seri_no'); // 버튼의 data-seri_no 속성 값
  console.log('seriNo:', seriNo);
  var inputSeriNo = addTaskModal.querySelector('#seri_no'); // 모달 내의 seri_no 입력 필드
  console.log('입력 필드:', inputSeriNo);
  inputSeriNo.value = seriNo; // 입력 필드에 seri_no 값 설정
});

// 행 삭제
document.querySelectorAll('.btn-delete-db').forEach((button) => {
  button.addEventListener('click', function () {
    const row = this.closest('tr'); // 버튼이 속한 행을 찾음
    const rowId = row.dataset.id; // 행의 고유 ID를 가져옴, 데이터 속성을 사용

    fetch('/delete-row', {
      method: 'POST',
      body: JSON.stringify({ rowId: rowId }),
      headers: {
        'Content-Type': 'application/json',
      },
    })
      .then((response) => response.json()) // 서버로부터 JSON 응답을 파싱
      .then((data) => {
        if (data.status === 'success') {
          console.log(data.message); // 성공 메시지 출력
        } else {
          console.error(data.message); // 실패 메시지 출력
        }
      })
      .catch((error) => {
        console.error('Error occurred:', error); // 네트워크 에러 처리
      });
  });
});
//작업 추가 모달에서 직접 입력 처리
function handleDirectInput(select) {
  if (select.value === 'direct_input') {
    var input = document.createElement('input');
    input.type = 'text';
    input.name = select.name;
    input.className = 'form-control';
    select.parentNode.replaceChild(input, select);
  }
}
$(document).ready(function () {
  // 모달 저장 버튼 클릭 이벤트 핸들러
  $('button[name="save_data"]').click(function () {
    $('#saveTask').submit(); // 폼 제출
  });
});
//검색을 위한 새로운 윈도우 창 열기
document.getElementById('searchButton').addEventListener('click', function () {
  window.open('task_search.php', '_blank');
});

// 항목 추가 모달을 호출하는 함수
function openAddOptionModal() {
  $('#addOptionModal').modal('show');
}

// 항목 호출 모달
// 항목 호출 모달
$(document).ready(function () {
  console.log('항목호출 모달');
  // 기존 모달에서 항목추가를 위해 새모달 호출
  $('#taskPartSelect').change(function () {
    if ($(this).val() == '항목추가') {
      openAddOptionModal(); // 새 항목 추가 모달을 열기
    }
  });

  // 모달 저장 버튼 클릭 이벤트 핸들러 추가
  $('#addOptionModal .btn-primary').click(function () {
    addNewTaskPart(); // 저장 버튼 클릭 시 addNewTaskPart 함수 호출
  });
});

// 작업 항목 불러오기
function addNewTaskPart() {
  var newTaskPart = $('input[name="hangmok"]').val(); // 모달 내의 항목명 입력값 가져오기
  var tpNo = $('input[name="tp_no"]').val(); // 모달 내의 항목번호 입력값 가져오기
  $.ajax({
    url: 'task_parts_save.php',
    type: 'POST',
    data: { add_task_part: true, taskpart: newTaskPart, tp_no: tpNo },
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
