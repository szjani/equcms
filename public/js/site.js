$(document).ready(function() {
  $('.nav a.list').prepend('<i class="icon-list"></i> ');
  $('.nav a.create').prepend('<i class="icon-plus-sign"></i> ');
  $('.nav a.update').prepend('<i class="icon-edit"></i> ');
  
  // disable URL parameter
  $('input[type="submit"]', $('form[method="get"]')).each(function() {
    $(this).removeAttr('name');
  });
});
