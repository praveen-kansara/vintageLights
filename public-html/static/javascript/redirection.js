var REDIRECTION = REDIRECTION || {};

REDIRECTION.PAGE = function() {
  this.initialize();
}

REDIRECTION.PAGE.prototype = {
  initialize: function() {
    this.setupBasics();
    this.setUpSideBar();
  },

  setUpSideBar: function() {
    var self = this;
    $("#btn_save_draft").click(function() {
      $("#status").val("draft");
      $('#redirection_manager').submit();
    });
    $("#btn_save_publish").click(function() {
      $("#status").val("published");
      $('#redirection_manager').submit();
    });

  },
  setupBasics: function() {
    var self = this;
    $("#page_name").keyup(function() {
      var pageName = $(this).val();
      var slug = pageName!="" ? self.slugify(pageName) : "";
      $("#page_slug").val(slug);
    });
    $(".btn-delete-action").click(function(e) {
      e.preventDefault();
      if (confirm("Are you sure you want to delete?") == true) {
        var deleteURL = $(this).attr('data-delete-path');
        location.href = deleteURL;
      }
    });
    $("#move_trash").click(function(e) {
      e.preventDefault();
      if (confirm("Do you really want to do it?") == true) {
        var deleteURL = "./?module="+$("input[name='module']").val()+"&action=Delete&id="+$("input[name='id']").val();
        location.href = deleteURL;
      }
    });
  },

}

$(document).ready(function() {
  new REDIRECTION.PAGE();
});

function doSort(fld, order) {
  document.searchForm.order_by.value = fld;
  document.searchForm.sort_order.value = order;
  Navigate(0);
}
