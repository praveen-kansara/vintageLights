var ENQUIRY = ENQUIRY || {};

ENQUIRY.ENQUIRY = function() {
  this.initialize();
}

ENQUIRY.ENQUIRY.prototype = {
  initialize: function() {
    this.setupBasics();
  },
  setupBasics: function() {
    var self = this;
  }
}

$(document).ready(function() {
  new ENQUIRY.ENQUIRY();
});

function doSort(fld, order) {
  document.searchForm.order_by.value = fld;
  document.searchForm.sort_order.value = order;
  Navigate(0);
}

/* Export employee list functionality */
function doExportEmployee() {
  document.searchForm.module.value = "Enquiry";
  document.searchForm.action.value = "EnquiryCSVExport";
  document.searchForm.submit();
  document.searchForm.module.value = "Enquiry";
  document.searchForm.action.value = "ListView";
}


$(document).ready(function () {

  $('#fromdate').datepicker({

    format: 'yyyy-mm-dd',
    autoclose: true,
    changeYear: true,
    changeMonth: true,
    showMeridian: false,
    todayHighlight: true,
    }).on('changeDate', function(){
      $('#todate').datepicker('setStartDate', new Date($(this).val()));
    }); 

  $('#todate').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    changeYear: true,
    changeMonth: true,
    showMeridian: false,
    todayHighlight: true,
    }).on('changeDate', function() {
        $todate = $('#todate').val();
        $fromdate = $('#fromdate').val();
        if($fromdate > $todate ){
          $fromdate = $('#fromdate').val($todate);
        }
    });

  // Read more / less functionality
  $(".read-toggle").click(function(){
    $(this).siblings('.short_desc').hide();
    $(this).next('.description').slideToggle("fast");
    $(this).siblings('.read-less').show();
    $(this).hide();
  });

  $(".read-less").click(function(){
    $(this).siblings('.short_desc').show();
    $(this).prev('.description').slideToggle("fast");
    $(this).siblings('.read-toggle').show();
    $(this).hide();
  });

  $('.info-button').popover({ trigger: "hover" });

});

