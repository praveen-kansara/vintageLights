var EDIT_VAR = EDIT_VAR || {};

EDIT_VAR.Editable = function() {
  this.initialize();
}

EDIT_VAR.Editable.prototype = {
  initialize: function () {
    this.editableSetup();
  },
  editableSetup: function() {
    var self = this;
    var editableTable = $(".editable-table");
    var quick_options = $(".quick-options");
    var editable_rows = $(".editable-row");
    var _metaRow = "";
    var rowBeingEdited, hiddenRowBeingEdited;

    quick_options.mouseover(function() {
      if(!$(this).hasClass('being-edited')) {
        $(this).find('.span-edit').removeClass('hidden');
      }
    });

    quick_options.mouseout(function() {
      $(this).find('.span-edit').addClass('hidden');
    });

    quick_options.each(function() {
      $(this).append('<div class="row-actions"><span class="span-edit action-span hidden"><a href="#"><i class="fa fa-pencil" style="font-size:11px;"></i>&nbsp;&nbsp;Quick Edit</a> </span></div>');
    });

    $(".editable-table span.span-edit").click(function() {
      $(".hidden-rows").addClass('hidden');
      $(".editable-row").removeClass('hidden');
      rowBeingEdited = $(this).closest("tr.editable-row");
      var editedRowID = rowBeingEdited.attr("id");
      hiddenRowBeingEdited = $("#hid-"+editedRowID);
      rowBeingEdited.addClass('hidden');
      hiddenRowBeingEdited.removeClass('hidden');
    });

    $(".editable-table").on('click', '.btn-update-quick-edit', function(e) {
      e.preventDefault();
      var jsonObj = {} ;
      jsonObj['type'] = "ajax";
      jsonObj['id'] = rowBeingEdited.attr('data-field-id');
      hiddenRowBeingEdited.find('.editable-cell').each(function() {
        var edit_type = $(this).attr("data-edit-type");
        switch(edit_type) {
          case "text":
          var user_value = $(this).find('input').val();
          var table_column = $(this).attr('data-field-name');
          jsonObj[[table_column]] = user_value;
          break;
          case "textarea":
          var user_value = $(this).find('textarea').val();
          var table_column = $(this).attr('data-field-name');
          jsonObj[[table_column]] = user_value;
          break;
          case "select":
          var user_value = $(this).find('select').find('option:selected').val();
          var table_column = $(this).attr('data-field-name');
          jsonObj[[table_column]] = user_value;
          break;
        }
      });
      var _url = rowBeingEdited.attr('data-stub-action');
      $.ajax({
        url: _url,
        type: "POST",
        dataType: "JSON",
        data: jsonObj,
        success: function (data) {
          $.bootstrapGrowl(data.message, { type: 'success' });
          rowBeingEdited.find('.quick-options').removeClass('being-edited');
          hiddenRowBeingEdited.find('.editable-cell').each(function(i, data) {
            var edit_type = $(this).attr("data-edit-type");
            var edit_field_name = $(this).attr("data-field-name");
            switch(edit_type) {
              case "text":
              var user_value = $(this).find('input').val();
              rowBeingEdited.find('.editable-cell[data-field-name="'+edit_field_name+'"]').find('span.val').html(user_value);
              break;
              case "select":
              var user_value = $(this).find('select').find('option:selected').val();
              rowBeingEdited.find('.editable-cell[data-field-name="'+edit_field_name+'"]').find('span.val').html(user_value  );
              break;
            }
          });
          rowBeingEdited.removeClass('hidden');
          hiddenRowBeingEdited.addClass('hidden');
        },
        error: function (data) {
          console.log(data);
          $.bootstrapGrowl("Something went wrong on server.", { type: 'error' });
        }
      });
    });

    /* Cancel icon */
    $(".editable-table").on('click', '.btn-cancel-quick-edit', function(e) {
      e.preventDefault();
      hiddenRowBeingEdited.addClass('hidden');
      rowBeingEdited.removeClass('hidden');
    });

    $(".span-edit > a").click(function(e) {
      // Cancel the default action
      e.preventDefault();
    });

  }
}

new EDIT_VAR.Editable();
