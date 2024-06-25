$(document).ready(function () {
  console.log('Document is ready');
  initializeEventHandlers();
});

function initializeEventHandlers() {
  console.log('Initializing event handlers...');
  $('#searchInput').on('keyup', handleSearchInput);
  // console.log('Keyup event on #searchInput');
  $('#oneYearBtn').click(() => setPeriod('1year'));
  // console.log('Clicked on #oneYearBtn');
  $('#threeYearsBtn').click(() => setPeriod('3years'));
  // console.log('Clicked on #threeYearsBtn');
  $('#yearSelect').change((event) => handleYearChange(event.target.value));
}

function handleSearchInput() {
  // console.log('handleSearchInput called'); // 함수 호출 확인 로그
  var input = $('#searchInput').val();
  // console.log('Current input:', input); // 현재 입력값 로그

  if (input.length > 0) {
    // console.log('Input length is greater than 1, performing search.'); // 검색 수행 로그
    performSearch(input);
  } else {
    $('#searchResultContainer').html('');
    console.log('Input length less than 2, cleared search results.'); // 입력 길이 조건 불충족 로그
  }
}

function performSearch(input) {
  var period = $('.btn-primary').data('period'); // 현재 선택된 기간 가져오기
  $.ajax({
    url: 'searchajax_s.php', // 매출 검색을 위한 서버 스크립트 URL
    type: 'POST',
    data: { input: input, period: period },
    success: function (response) {
      $('#searchResultContainer').html(response);
    },
    error: function () {
      alert('검색 중 오류가 발생했습니다.');
    },
  });
}

function setPeriod(period) {
  $('.btn-primary').data('period', period); // 기간 데이터 설정
  performSearch($('#searchInput').val()); // 기간 변경 후 검색 재실행
}

function handleYearChange(selectedYear) {
  console.log('선택된 연도:', selectedYear);
  setPeriod(selectedYear);
}
