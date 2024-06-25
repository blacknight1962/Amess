$(document).ready(function () {
  $('#getWords').keyup(function () {
    let input = $(this).val();
    // /*alert(input);*/
    // /*console.log(getwords);*/
    if (input != '') {
      $.ajax({
        method: 'POST',
        url: 'searchajax_job.php',
        data: {
          input: input,
        },
        success: function (response) {
          $('#searchresult_j').html(response);
        },
      });
    } else {
      $('searchresult_j').css('display', 'none');
    }
  });
});
$(document).ready(function () {
  console.log('Document is ready');
  initializeEventHandlers();
});

function initializeEventHandlers() {
  console.log('Initializing event handlers...');
  $('#getWords').on('keyup', handleSearchInput);
  $('#oneYearBtn').click(() => setPeriod('1year'));
  $('#threeYearsBtn').click(() => setPeriod('3years'));
  $('#yearSelect').change((event) => handleYearChange(event.target.value));
}

function handleSearchInput() {
  var input = $('#getWords').val();

  if (input.length > 0) {
    performSearch(input);
  } else {
    $('#searchresult_j').html('');
    console.log('Input length less than 2, cleared search results.');
  }
}

function performSearch(input) {
  var period = $('.btn-primary').data('period'); // 현재 선택된 기간 가져오기
  $.ajax({
    url: 'searchajax_job.php', // 작업코드 검색을 위한 서버 스크립트 URL
    type: 'POST',
    data: { input: input, period: period },
    success: function (response) {
      $('#searchresult_j').html(response);
    },
    error: function () {
      alert('검색 중 오류가 발생했습니다.');
    },
  });
}

function setPeriod(period) {
  $('.btn-primary').data('period', period); // 기간 데이터 설정
  performSearch($('#getWords').val()); // 기간 변경 후 검색 재실행
}

function handleYearChange(selectedYear) {
  console.log('선택된 연도:', selectedYear);
  setPeriod(selectedYear);
}
