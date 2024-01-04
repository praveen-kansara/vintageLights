 $(".perg-cache").click(function(e) {
    e.preventDefault();
    if (confirm("Are you sure you want to delete all cache?") == true) {
      var deleteURL = $(this).attr('purge-path');
      location.href = deleteURL;
    }
});
 
 $(".minify").click(function(e) {
    e.preventDefault();
    if (confirm("Are you sure you want to minify static files?") == true) {
      var deleteURL = $(this).attr('minify-path');
      location.href = deleteURL;
    }
});
 

$( document ).ready(function() {

  $('#settings_page').areYouSure(
    {
      message: 'It looks like you have been editing something. '
             + 'If you leave before saving, your changes will be lost.'
    }
  );
});
