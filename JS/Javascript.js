/*Make sure you keep the scroll position after reloading*/
$(window).scroll(function() {
  sessionStorage.scrollTop = $(this).scrollTop();
});

$(document).ready(function() {
  if (sessionStorage.scrollTop != "undefined") { $(window).scrollTop(sessionStorage.scrollTop); }
  $('.image').css('height', $('.storeHeight').height());
});
