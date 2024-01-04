$("#email").blur(function(){
    email    = $("#email").val();
    id       = $("#cuser_id").val();
    $.ajax({ 
	    type: 'POST', 
	    url: './?module=Ajax&action=AjaxCheckDupliateEmail', 
	    data: { email:email, id:id}, 
	    success: function (data){ 
			if(data == 1){
				$("#email").val('');	
				alert("email already exist");
			}

		}

	});


});

function Navigate(o) {
  document.searchForm.offset.value = o;
  document.searchForm.submit();
}

function PageNavigate(o) {
  document.searchForm.p.value = o;
  document.searchForm.submit();
}

function doSort(fld, order) {

  document.searchForm.order_by.value = fld;
  document.searchForm.sort_order.value = order;
  Navigate(0);
}

function Start0() {
  document.searchForm.offset.value = 0;
  document.searchForm.submit();
}

$(".btn-delete-action").click(function(e) {
  e.preventDefault();
  if (confirm("Are you sure you want to delete?") == true) {
    var deleteURL = $(this).attr('data-delete-path');
    location.href = deleteURL;
  }
});

 $(function() {
  $('#SystemUserEdit').areYouSure(
    {
      message: 'It looks like you have been editing something. '
             + 'If you leave before saving, your changes will be lost.'
    }
  );
