document.addEventListener('DOMContentLoaded', function () {
  const keywordInput = document.getElementById('getWords');
  const resetBtn = document.getElementById('resetBtn');
  const searchResultContainer = document.getElementById(
    'searchResultContainer'
  );
  const yearInput = document.getElementById('yearSelect');
  const selectedPeriodInput = document.getElementById('selectedPeriod');
  let page = 1;
  let isLoading = false;

  function performSearch() {
    if (isLoading) return;
    isLoading = true;

    const keyword = keywordInput.value;
    const year = yearInput.value;
    const period = selectedPeriodInput.value;

    const params = new URLSearchParams({
      input: keyword,
      year,
      period,
      page,
    });

    fetch('searchajax_o.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: params.toString(),
    })
      .then((response) => response.text())
      .then((html) => {
        searchResultContainer.innerHTML += html;
        isLoading = false;
        page++;
      })
      .catch((error) => {
        console.error('Error:', error);
        isLoading = false;
      });
  }

  function handleYearChange() {
    page = 1;
    searchResultContainer.innerHTML = '';
    performSearch();
  }

  if (keywordInput) {
    keywordInput.addEventListener('input', function () {
      page = 1;
      searchResultContainer.innerHTML = '';
      performSearch();
    });
  }

  if (yearInput) {
    yearInput.addEventListener('change', handleYearChange);
  }

  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      keywordInput.value = '';
      yearInput.value = '';
      selectedPeriodInput.value = '';
      searchResultContainer.innerHTML = ''; // 검색 결과 초기화
      window.location.href = 'order_index.php'; // 페이지를 새로고침하여 본래 화면으로 돌아감
    });
  }

  window.addEventListener('scroll', function () {
    if (
      window.innerHeight + window.scrollY >= document.body.offsetHeight - 500 &&
      !isLoading
    ) {
      performSearch();
    }
  });

  // handleYearChange 함수를 전역으로 노출
  window.handleYearChange = handleYearChange;
});
