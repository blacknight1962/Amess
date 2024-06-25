$(document).ready(function () {
  initializeEventHandlers();
  $('#TBody .sub_no').first().val(1);
});

$(document).ready(function () {
  $('#editEquipData').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // 이벤트를 발생시킨 버튼
    var equipmentId = button.attr('data-equipmentId'); // attr() 메소드로 data-equipmentId 값 추출

    // 콘솔에 equipmentId 값을 출력하여 확인
    console.log('Equipment ID fetched:', equipmentId);

    // 모달 내의 적절한 위치에 equipmentId 값을 설정합니다.
    $('#editEquipData').text('e_no: ' + equipmentId);
    $(this).find('tbody').data('#equipmentId', equipmentId);
  });
});

// 데이터 속성에서 e_no 읽기
var equipmentId = $('.modal-body').data('equipmentId');

// 다른 함수에서 currentEquipmentId 사용
function someOtherFunction() {
  console.log('Using equipment ID:', currentEquipmentId);
}

//삭제를 위해 이벤트 처리 전담 핸들러
$(document).on('click', '.delete-button', function () {
  var equipId = $(this).data('equip-id');
  if (confirm('이 장비를 삭제하시겠습니까?')) {
    deleteEquipment(equipId);
  }
});

// 행 삭제
function BtnDel(element) {
  if (confirm('이 행을 삭제하시겠습니까?')) {
    var row = $(element).closest('tr');
    var subNo = row.find('.sub_no').val(); // sub_no 추출
    var eNo = row.data('e-no'); // e_no 추출, HTML에서 data-e-no 속성을 설정해야 함

    // 데이터베이스에서 행 삭제
    deletefacilityRow(subNo, eNo);

    // 화면에서 행 삭제
    row.remove();

    // sub_no 재정렬
    updateSubNos();
  }
}
// 행삭제 2단계

function deleteEquipment(equipId) {
  $.ajax({
    url: 'equip_process.php',
    type: 'POST',
    data: { id: equipId },
    success: function () {
      alert('성공적으로 삭제되었습니다.');
      location.reload();
    },
    error: function () {
      alert('삭제 처리 중 오류가 발생했습니다.');
    },
  });
}

function initializeEventHandlers() {
  console.log('Event handlers initialized');
  $('#searchInput').on('keyup', handleSearchInput);
  $('#oneYearBtn').click(() => setPeriod('1year'));
  $('#threeYearsBtn').click(() => setPeriod('3years'));
  $('#yearSelect').change((event) => handleYearChange(event.target.value));
}

function handleSearchInput() {
  var input = $(this).val();

  if (input.length > 1) {
    performSearch(input);
  } else {
    $('#equipmentTableBody').html('');
  }
}

function performSearch(input) {
  $.ajax({
    url: 'searchajax_e.php',
    type: 'POST',
    data: { input: input },
    success: function (response) {
      $('#equipmentTableBody').html(response);
    },
    error: function () {
      alert('검색 중 오류가 발생했습니다.');
    },
  });
}
function handleYearChange(selectedYear) {
  console.log('선택된 연도:', selectedYear);
  fetchQuotesByPeriod(selectedYear);
}

$(document).ready(function () {
  $('#yearSelect').change(function () {
    var selectedYear = $(this).val();
    handleYearChange(selectedYear);
  });
});

function setPeriod(period) {
  fetchQuotesByPeriod(period);
}

function fetchQuotesByPeriod(period) {
  //  console.log('Fetching data for period:', period);
  $.ajax({
    url: 'fetch_equip.php',
    type: 'GET',
    data: { period: period },
    success: function (data) {
      $('#equipmentTableBody').html(data);
    },
    error: function (xhr) {
      //      console.error('Error fetching data:', xhr.responseText);
      alert('데이터를 불러오는 데 실패했습니다.');
    },
  });
}

$('#insertdata').on('shown.bs.modal', function () {
  $(this).find('.modal-dialog').css({
    width: '1800px', // 원하는 너비 설정
    height: '90vh', // 원하는 높이 설정
    'max-width': 'none', // 최대 너비 해제
  });
});

$('.btn-secondary').click(function () {
  var modal = bootstrap.Modal.getInstance(
    document.getElementById('editEquipData')
  );
  modal.hide();
});

function openPopupWindow(equipmentId) {
  var url = 'equip_update.php?id=' + equipmentId;
  var windowName = 'EditEquipment';
  var windowFeatures = 'width=1800,height=300';
  window.open(url, windowName, windowFeatures);
}
//통합검색을 위한 새로운 윈도우 창 열기
document.getElementById('searchButton').addEventListener('click', function () {
  window.open('task_search.php', '_blank');
});
