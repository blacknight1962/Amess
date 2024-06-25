// task_search.js
$(document).ready(function () {
  initializeEventHandlers1();
  initializeEventHandlers2();
});

function initializeEventHandlers1() {
  console.log('Initializing event handlers1...');
  $('#searchInput1').on('keyup', handleSearchInput1);
  $('#oneYearBtn1').click(() => setPeriod('1year'));
  $('#threeYearsBtn1').click(() => setPeriod('3years'));
  $('#yearSelect1').change((event) => handleYearChange(event.target.value));
}

function handleSearchInput1() {
  var input = $(this).val();

  if (input.length > 1) {
    performSearch1(input);
  } else {
    $('#facilityEquipmentBody').html('');
  }
}

function performSearch1(input) {
  var period = $('.btn-primary').data('period'); // 현재 선택된 기간 가져오기
  $.ajax({
    url: 'task_searchajax_f.php',
    type: 'POST',
    data: { input: input, period: period },
    success: function (response) {
      $('#facilityEquipmentBody').html(response);
    },
    error: function () {
      alert('검색 중 오류가 발생했습니다.');
    },
  });
}
function initializeEventHandlers2() {
  console.log('Initializing event handlers2...');
  $('#searchInput2').on('keyup', handleSearchInput2);
  $('#oneYearBtn2').click(() => setPeriod('1year'));
  $('#threeYearsBtn2').click(() => setPeriod('3years'));
  $('#yearSelect2').change((event) => handleYearChange(event.target.value));
}
function handleSearchInput2() {
  var input = $(this).val();

  if (input.length > 1) {
    performSearch2(input);
  } else {
    $('#taskSearchBody').html('');
  }
}

function performSearch2(input) {
  var period = $('.btn-primary').data('period'); // 현재 선택된 기간 가져오기
  $.ajax({
    url: 'task_searchajax_t.php',
    type: 'POST',
    data: { input: input, period: period },
    success: function (response) {
      $('#taskSearchBody').html(response);
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

// function setPeriod(period) {
//   fetchQuotesByPeriod(period);
// }
// function setPeriod(period) {
//   $('.btn-primary').data('period', period); // 기간 데이터 설정
//   fetchQuotesByPeriod(period);
// }
$(document).ready(function () {
  $('.period-btn-equip').click(function () {
    $('.period-btn-equip')
      .removeClass('btn-primary')
      .addClass('btn-outline-primary');
    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
    setPeriod('equip', $(this).data('period'));
  });

  $('.period-btn-task').click(function () {
    $('.period-btn-task')
      .removeClass('btn-primary')
      .addClass('btn-outline-primary');
    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
    setPeriod('task', $(this).data('period'));
  });
});

function setPeriod(type, period) {
  if (type === 'equip') {
    $.ajax({
      url: 'task_searchajax_f.php',
      type: 'POST',
      data: { period: period },
      success: function (data) {
        $('#facilityEquipmentBody').html(data);
      },
      error: function () {
        alert('데이터를 불러오는 데 실패했습니다.');
      },
    });
  } else if (type === 'task') {
    $.ajax({
      url: 'task_searchajax_t.php',
      type: 'POST',
      data: { period: period },
      success: function (data) {
        $('#taskSearchBody').html(data);
      },
      error: function () {
        alert('데이터를 불러오는 데 실패했습니다.');
      },
    });
  }
}

function fetchQuotesByPeriod(period) {
  $.ajax({
    url: 'task_searchajax_f.php', // URL을 task_search.php에 맞게 조정
    type: 'GET',
    data: { period: period },
    success: function (data) {
      $('#facilityEquipmentBody').html(data);
    },
    error: function (xhr) {
      alert('데이터를 불러오는 데 실패했습니다.');
    },
  });
}
//특정 기간 선택시 버튼 색상 변경
$(document).ready(function () {
  $('#oneYearBtn1').click(function () {
    $('.btn').removeClass('btn-primary').addClass('btn-outline-primary');
    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
    setPeriod('1year');
  });

  $('#threeYearsBtn1').click(function () {
    $('.btn').removeClass('btn-primary').addClass('btn-outline-primary');
    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
    setPeriod('3years');
  });
});
$(document).ready(function () {
  $('#oneYearBtn2').click(function () {
    $('.btn').removeClass('btn-primary').addClass('btn-outline-primary');
    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
    setPeriod('1year');
  });

  $('#threeYearsBtn2').click(function () {
    $('.btn').removeClass('btn-primary').addClass('btn-outline-primary');
    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
    setPeriod('3years');
  });
});
// 검색결과 내 행 클릭 시 해당 자료 조회
// document.addEventListener('DOMContentLoaded', function () {
//   const table = document.getElementById('data-table');
//   table.addEventListener('click', function (e) {
//     const targetRow = e.target.closest('tr');
//     if (targetRow) {
//       const eNo = targetRow.getAttribute('data-e_no');
//       const subNo = targetRow.getAttribute('data-sub_no');
//       const seriNo = targetRow.getAttribute('data-seri_no');
//       window.open(
//         `task_manage.php?e_no=${eNo}&sub_no=${subNo}&seri_no=${seriNo}`,
//         '_blank'
//       );
//     }
//   });
// });
