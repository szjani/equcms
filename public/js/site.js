$(document).ready(function() {
  $('.nav a.list').prepend('<i class="icon-list"></i> ');
  $('.nav a.create').prepend('<i class="icon-plus-sign"></i> ');
  $('.nav a.update').prepend('<i class="icon-edit"></i> ');
  
  // disable URL parameter
  $('input[type="submit"]', $('form[method="get"]')).each(function() {
    $(this).removeAttr('name');
  });
  
  function autoSetBodyPadding() {
    if ($(window).outerWidth() > 980) {
      $('body').css('padding-top', $('.navbar-fixed-top').outerHeight() + 12);
    } else {
      $('body').css('padding-top', 0);
    }
  }
  
  $(window).resize(function() {
    autoSetBodyPadding();
  });
  
  autoSetBodyPadding();
});
