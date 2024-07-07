$(document).ready(function () {
  console.log('Document is ready');
  initializeEventHandlers();
});

function initializeEventHandlers() {
  console.log('Initializing event handlers...');
  $(document).on('keyup', '#searchfacility', handleSearchInput);
}

function handleSearchInput() {
  var input = $('#searchfacility').val();
  console.log('Input:', input);
  if (input.length > 0) {
    performSearch(input);
  } else {
    $('#searchResultContainer_f').html('');
    console.log('Input length less than 2, cleared search results.');
  }
}

function performSearch(input) {
  console.log('Performing search with:', { input });

  $.ajax({
    url: 'searchajax_fM.php', // 발주 검색을 위한 서버 스크립트 URL
    type: 'POST',
    data: { input: input },
    success: function (response) {
      console.log('AJAX success:', response);
      $('#searchResultContainer_f').html(response);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert('검색 중 오류가 발생했습니다.');
    },
  });
}
