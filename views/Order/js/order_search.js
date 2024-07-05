$(document).ready(function () {
  console.log('Document is ready');
  initializeEventHandlers();
});

function initializeEventHandlers() {
  console.log('Initializing event handlers...');
  $(document).on('keyup', '#getWords', handleSearchInput);
  $('#oneYearBtn').click(() => setPeriod('1year'));
  $('#threeYearsBtn').click(() => setPeriod('3years'));
  $('#yearSelect').change((event) => handleYearChange(event.target.value));
}

function handleSearchInput() {
  var input = $('#getWords').val();
  console.log('Input:', input);
  if (input.length > 0) {
    performSearch(input);
  } else {
    $('#searchResultContainer_o').html('');
    console.log('Input length less than 2, cleared search results.');
  }
}

function performSearch(input) {
  var period = $('.btn-primary').data('period'); // 현재 선택된 기간 가져오기
  var year = $('#yearSelect').val(); // 선택된 연도 가져오기
  console.log('Performing search with:', { input, period, year });

  $.ajax({
    url: 'searchajax_o.php', // 발주 검색을 위한 서버 스크립트 URL
    type: 'POST',
    data: { input: input, period: period, year: year },
    success: function (response) {
      console.log('AJAX success:', response);
      $('#searchResultContainer_o').html(response);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert('검색 중 오류가 발생했습니다.');
    },
  });
}

function setPeriod(period) {
  $('.btn-primary').data('period', period); // 기간 데이터 설정
  $('#yearSelect').val(''); // 기간 선택 시 연도 초기화
  performSearch($('#getWords').val()); // 기간 변경 후 검색 재실행
}

function handleYearChange(selectedYear) {
  console.log('선택된 연도:', selectedYear);
  $('.btn-primary').data('period', ''); // 연도 선택 시 기간 초기화
  performSearch($('#getWords').val()); // 연도 변경 후 검색 재실행
}
