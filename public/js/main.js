toggleBtn.addEventListener('click', () => {
  menu.classList.toggle('active');
  icons.classList.toggle('active');
});

//
$('.sepe_in').click(function (e) {
  e.preventDefault();
  //$("#sepe_in").css("display", "block");
  //$("#sepe_in").show();
  //$("#sepe_in").fadeIn();
  $('sepe_in').slideDown();
});

$('.sepe_in .close').click(function (e) {
  e.preventDefault();
  //$("#sepe_in").css("display", "block");
  //$("#sepe_in").show();
  //$("#sepe_in").fadeIn();
  $('sepe_in').slideUp();
});
