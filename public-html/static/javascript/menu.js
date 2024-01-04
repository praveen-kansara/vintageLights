var HANDLEMENU = HANDLEMENU || {};

HANDLEMENU.MENU = function() {
  this.initialize();
}

HANDLEMENU.MENU.prototype = {
  initialize: function () {
    this.basicSetUp();
  },

  generateUUID: function () {
    var d = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x3|0x8)).toString(16);
    });
    return uuid;
  },

  basicSetUp: function() {

    var self = this;

    $('.div-pages').slimScroll({
      height: '100px'
    });

    function resequence() {
      var menu_list = $('.sortable li');
      menu_list.each(function(i, data) {
        $(this).attr('id', 'menu_'+(i+1));
      });
    }

    var ns = $('ol.sortable').nestedSortable({
      forcePlaceholderSize: true,
      handle: 'div',
      helper:	'clone',
      items: 'li',
      opacity: .6,
      placeholder: 'placeholder',
      revert: 250,
      tabSize: 25,
      tolerance: 'pointer',
      toleranceElement: '> div',
      maxLevels: 3,
      isTree: true,
      expandOnHover: 700,
      startCollapsed: false,
      relocate: function() {
        resequence();
      }
    });

    $("#save_menu").click(function() {
      var arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
      var menu_list = $('.sortable li');
      var prev_parent_id;
      menu_list.each(function(i, data) {
        arraied[i+1].page_id = $(this).attr('data-page-id');
        arraied[i+1].name = $(this).find('.navigation-label').val();
        arraied[i+1].css_class = $(this).find('.css-class').val();
        arraied[i+1].display_sequence = i+1;
        if(arraied[i+1].parent_id != null) {
          arraied[i+1].parent_id = $("#menu_"+arraied[i+1].parent_id).attr('data-menu-id');
        }
        else {
          arraied[i+1].parent_id = "";
        }
        arraied[i+1].id = $(this).attr('data-menu-id');
        arraied[i+1].url = $(this).find('.page-url').val();
        arraied[i+1].type = $(this).find('.page-type').val();
        
        if($(this).find('.open-new-tab').is(':checked')) {
          arraied[i+1].target = "_blank";
        }
        else {
          arraied[i+1].target = "";
        }
      });
      console.log(arraied);
      $.ajax({
        url: "./?module=Ajax&action=SaveMenu",
        type: "POST",
        data: { menus : arraied , menu_id: $("#select-menu-to-edit").val() },
        dataType: "json",
        error: function(data) {
          console.log(data);
        },
        success: function (data) {
          console.log(data);
          $.bootstrapGrowl("Menu saved successfully.", { type: 'success' });
        },
        error: function(data) {
          console.log(data);
          $.bootstrapGrowl("Something went wrong.", { type: 'error' });
        }
      });
    });
    
    $("#add_new_menu, #add_new_service_menu").click(function() {
      $(".page-ids").each(function(i, data) {
        if($(this).is(":checked")) {
          var page_id = $(this).val();
          var page_title = $(this).attr('data-page-name');
          var html = $('<li data-menu-id="'+self.generateUUID()+'" data-page-id="'+page_id+' class="mjs-nestedSortable-branch"><div class="menuDiv"><div class="menu-title"><span><span class="itemTitle">'+page_title+'</span><span class="expand-edit ui-icon	ui-icon-triangle-1-s"><span></span></span></span></div><div class="menuEdit hidden"><div class=""><form role="form"><div class="box-body"><div class="form-group"><label>Navigation Label</label><input type="text" class="form-control navigation-label input-sm" value="'+page_title+'" placeholder="Enter navigation label"></div><div class="form-group"><label class="mt-checkbox mt-checkbox-outline"><input value="_blank" type="checkbox" class="open-new-tab"> Open link in new tab<span></span></label></div><div class="form-group"><label>Css Class (optional)</label><input type="text" class="form-control css-class input-sm" value="" /></div><div><a href="#" class="remove-menu">Remove</a></div></div><input type="hidden" class="page-type" value="page"/><input type="hidden" class="page-url" value=""/></form></div></div></div></li>');
          ns.append($(html));
          $(this).attr('checked', false);
          resequence();
        }
      });
    });

    $("#select-menu-to-edit").change(function() {
      var mid = $(this).val();
      location.href = "?module=Menu&action=index&menu="+mid;
    });

    $("#delete_menu").click(function(e) {
      e.preventDefault();
      if (confirm("Are you sure you want to delete?") == true) {
        var deleteURL = $(this).attr('data-delete-path');
        deleteURL = deleteURL+$("#select-menu-to-edit").val();
        location.href = deleteURL;
      }
    });

    $("#add_new_custom_menu").click(function() {

      var menu_title = $('.navigation-label-custom').val();
      var url = $(".url-custom").val();
      var html = $('<li data-menu-id="'+self.generateUUID()+'" data-page-id="" class="mjs-nestedSortable-branch"><div class="menuDiv"><div class="menu-title"><span><span class="itemTitle">'+menu_title+'</span><span class="expand-edit ui-icon	ui-icon-triangle-1-s"><span></span></span></span></div><div class="menuEdit hidden"><div class=""><form role="form"><div class="box-body"><div class="form-group"><label>Navigation Label</label><input type="text" class="form-control navigation-label input-sm" value="'+menu_title+'" placeholder="Enter navigation label"></div><div class="form-group"><label>URL</label><input type="text" class="form-control page-url input-sm" value="'+url+'" placeholder="Enter url"></div><div class="form-group"><label class="mt-checkbox mt-checkbox-outline"><input value="_blank" type="checkbox" class="open-new-tab"> Open link in new tab<span></span></label></div><div class="form-group"><label>Css Class (optional)</label><input type="text" class="form-control css-class input-sm" value="" /></div><div><a href="#" class="remove-menu">Remove</a></div></div><input type="hidden" class="page-type" value="custom"/></form></div></div></div></li>');
      ns.append($(html));
      
      $('.navigation-label-custom').val("");
      $('.url-custom').val("");

      resequence();

    });

    $(".sortable").on('click', '.expand-edit', function() {
      $(this).closest('.menuDiv').find('.menuEdit').toggleClass('hidden');
      if($(this).hasClass('ui-icon-triangle-1-s')) {
        $(this).removeClass('ui-icon-triangle-1-s').addClass('ui-icon-triangle-1-n');
      }
      else {
        $(this).removeClass('ui-icon-triangle-1-n').addClass('ui-icon-triangle-1-s');
      }
    });

    $(".sortable").on('click', '.remove-menu', function(e) {
      e.preventDefault();
      $(this).closest('li').remove();
      resequence();
    });

  }
}

$(document).ready(function() {
  new HANDLEMENU.MENU();
});
