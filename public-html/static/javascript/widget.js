var WIDGET = WIDGET || {};

WIDGET.PAGE = function() {
  this.initialize();
}

WIDGET.PAGE.prototype = {
  initialize: function() {
    this.setupBasics();
    this.slugSetUp();
    this.setUpSideBar();
    this.dropZoneSetUp();
    this.initializeTinyMCE();
  },
  contentImageSetUp: function() {
    var self = this;
    var modalTemplate = $("#image_lib_widget");
    modalTemplate.find('.footer').html('<select class="hidden form-control mini-select" id="select_image_sizes"></select><button id="btn_get_image_tag" type="button" class="_custom-button">Insert Image</button><button id="btn_close_popup" type="button" class="_custom-button">Close</button><div class="div-selected-image-path hidden"><a class="copy-clipboard-link" href="">Copy to Clipboard</a><textarea class="form-control"></textarea></div>');
    modalTemplate.find("#btn_get_image_tag").click(function() {
      var selectedImage = $(".images-list .selected .med-image");
      var attributes = JSON.parse(selectedImage.attr('data-attributes'));
      var imageName = selectedImage.attr('data-original-name');
      var imageTag = "<img data-image-name='"+imageName+"' src='"+modalTemplate.find("#select_image_sizes").val()+"' alt='"+attributes.alt+"' title='"+attributes.caption+"' />";
      self.editor.insertContent(imageTag);
      modalTemplate.hide();
    });
    modalTemplate.find("#btn_close_popup").click(function() {
      modalTemplate.hide();
    });
    modalTemplate.find(".close-popup").click(function() {
      modalTemplate.hide();
    });
    modalTemplate.show();
    self.loadImages(modalTemplate);
  },
  dropZoneSetUp: function() {
    var modalTemplate = $("#image_lib_widget");
    var list = modalTemplate.find('.images-list');
    var myDropzone = new Dropzone("div#dropzone", {
      url: "./?module=Media&action=Save",
      createImageThumbnails: true,
      parallelUploads: 20,
      addRemoveLinks: false,
      maxFiles: 20,
      autoDiscover: false,
      autoProcessQueue: false,
      previewTemplate: '<li tabindex="0" class="attachment"><div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait"><div class="thumbnail"><div class="centered"> <img class="med-image img-responsive" src="" alt=""> </div><div class=dz-progress><span class=dz-upload data-dz-uploadprogress=""></span></div></div></div></li>',
      autoQueue: true,
      previewsContainer: "#popup_image_library_ul"
    });
    myDropzone.on("thumbnail", function(file) {
      setTimeout(function() {
        myDropzone.processQueue();
      }, 100);
    });
    myDropzone.on("addedfile", function(file) {
      modalTemplate.find('a[href="#tab_media_library"]').trigger('click');
    });
    myDropzone.on("success", function(file, response) {
      var server_response = JSON.parse(response);
      console.log(server_response);

      $(file.previewElement).find('.images-pop-anchor').attr('data-page-id', server_response.id);
      $(file.previewElement).find('.dz-progress').hide();
      $(file.previewElement).find('.med-image').attr('src', server_response.thumbnails[0]);

      $(file.previewElement).find('.med-image').attr('data-attributes', server_response.attributes);
      $(file.previewElement).find('.med-image').attr('src', server_response.thumbnails[0]);
      $(file.previewElement).find('.med-image').attr('data-original-path', server_response.original_image);
      $(file.previewElement).find('.med-image').attr('data-media-id', server_response.id);
      $(file.previewElement).find('.med-image').attr('data-thumbnail-sizes', (server_response.thumbnail_sizes).join());
      $(file.previewElement).find('.med-image').attr('data-thumbnail-paths', (server_response.thumbnails).join());

      $(file.previewElement).find('.med-image').attr('data-global-thumbnail-paths', (server_response.global_thumbnails_path).join());
      $(file.previewElement).find('.med-image').attr('data-global-path', server_response.global_image_path);

      $(file.previewElement).find('.med-image').attr('data-original-name', server_response.name);
      
      $(file.previewElement).find('.med-image').attr('alt', (server_response.thumbnails).join());

      $(file.previewElement).click(function() {
        var dataGlobalPath = $(file.previewElement).find('.med-image').attr('data-global-path');
        var attributes = JSON.parse($(file.previewElement).find('.med-image').attr('data-attributes'));
        var thumbnailSizes = ($(file.previewElement).find('.med-image').attr('data-thumbnail-sizes')).split(',');
        var globalthumbnailPaths = ($(file.previewElement).find('.med-image').attr('data-global-thumbnail-paths')).split(',');
        var options = "<option value='"+dataGlobalPath+"'>Original ("+attributes.width+"x"+attributes.height+  ")</option>";
        for (var i = 0; i < thumbnailSizes.length; i++) {
          options += "<option value='"+globalthumbnailPaths[i]+"'>"+thumbnailSizes[i]+"</option>"
        }
        modalTemplate.find("#select_image_sizes").removeClass('hidden').html(options);
      });

    });
    Dropzone.autoDiscover = false;
  },
  setUpSideBar: function() {
    var self = this;
    $("#btn_save_draft").click(function() {
      $("#status").val("draft");
      if(tinyMCE.activeEditor.hidden) {
        tinyMCE.execCommand('mceToggleEditor', false, 'content');
      }
      $('#form').submit();
    });
    $("#btn_save_publish").click(function() {
      $("#status").val("published");
      if(tinyMCE.activeEditor.hidden) {
        tinyMCE.execCommand('mceToggleEditor', false, 'content');
      }
      $('#form').submit();
    });
  },
  slugSetUp: function () {
    var pageId = $("input[name='id']").val();
    var self = this;
    var slugBox = $("#slug_block");
    $("#title").blur(function() {
      if(!slugBox.hasClass('slug-exists') && $("#title").val()!="") {
        var title = $(this).val();
        var slug = title!="" ? self.shortCode(title) : "";
        $.ajax({
          url: "./?module=Ajax&action=CheckShortCode",
          type: "POST",
          data: {
            short_code: slug,
            path: "",
            page_id: pageId
          },
          dataType: "json",
          error: function(data) {
            console.log(data);
          },
          success: function (data) {
            console.log(data);
            slugBox.find('span.permalink').html("<span data-short-code='"+data.short_code+"'>"+data.short_code+"</span>");
            slugBox.addClass("slug-exists");
            slugBox.find('p').removeClass('hidden');
            $("input[name='short_code']").val(data.short_code);
            $("#btn-slug-view_page").addClass('hidden');
          },
          error: function(data) {
            console.log(data);
          }
        });
      }
    });
    slugBox.find(".btn-slug-edit").click(function() {
      var slugSpan = slugBox.find('span.permalink').find('span');
      slugSpan.html("<input style='height:30px;' value='"+slugSpan.attr('data-short-code')+"'/>");
      slugBox.find(".btn-slug-edit").addClass('hidden');
      slugBox.find(".btn-slug-ok").toggleClass('hidden');
      slugBox.find(".btn-slug-cancel").toggleClass('hidden');
    });
    slugBox.find(".btn-slug-ok").click(function() {
      console.log(pageId);
      var slugSpan = slugBox.find('span.permalink').find('span');
      var slugInput = slugBox.find('span.permalink').find('span').find('input');
      $.ajax({
        url: "./?module=Ajax&action=CheckShortCode",
        type: "POST",
        data: {
          short_code: self.shortCode(slugInput.val()),
          path: "",
          page_id: pageId
        },
        dataType: "JSON",
        success: function (data) {
          console.log(data);
          slugSpan.attr('data-short-code', data.short_code);
          slugBox.find(".btn-slug-cancel").trigger('click');
          $("input[name='short_code']").val(data.short_code);
        },
        error: function(data) {
          console.log(data);
        }
      });
    });
    slugBox.find(".btn-slug-cancel").click(function() {
      var slugSpan = slugBox.find('span.permalink').find('span');
      slugSpan.html(slugSpan.attr('data-short-code'));
      slugBox.find(".btn-slug-edit").toggleClass('hidden');
      slugBox.find(".btn-slug-ok").toggleClass('hidden');
      slugBox.find(".btn-slug-cancel").toggleClass('hidden');
    });
  },
  shortCode: function(text) {
    return text.toString().toUpperCase()
    .replace(/\s+/g, '_')
    .replace(/[^\w\-]+/g, '')
    .replace(/\-\-+/g, '_')
    .replace(/^-+/, '')
    .replace(/-+$/, '');
  },
  setupBasics: function() {
    var self = this;
    $("#page_name").keyup(function() {
      var pageName = $(this).val();
      var slug = pageName!="" ? self.slugify(pageName) : "";
      $("#page_slug").val(slug);
    });
    $(".images-list").on('click', '.attachment', function() {
      $(".images-list .attachment").removeClass('selected');
      $(this).toggleClass('selected');
    });
    $("#btn_insert_image_media_lib").click(function() {
      var imageSource = $(".images-list .selected .med-image").attr('src');
      self.editor.insertContent('<img src="'+imageSource+'" draggable="false" alt="">');
      $('.image-library').popup('hide');
    });
    $(".banner-image").click(function() {
      self.bannerImageSetUp();
    });
    $(".remove-banner").click(function() {
      $('.banner-img').attr('src', '');
      $("input[name='banner_image']").val('');
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
    $("#insert_content_image").click(function() {
      self.contentImageSetUp();
    });
  },
  initializeTinyMCE: function () {
    var self = this;
    tinyMCE.init({
      selector: '#content',
      height: 250,
      mode : "textareas",
      valid_elements: '*[*]',
      relative_urls : false,
      remove_script_host : false,
      convert_urls : true,
      menubar: false,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code'
      ],
      toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link customimage',
      content_css: [
        '../static/front/css/bootstrap.min.css',
        '../static/front/css/style.css'
      ],
      setup: function(editor) {
        self.editor = editor;
        editor.addButton('customimage', {
          icon: 'mce-ico mce-i-image',
          tooltip : 'insert image',
          onclick: function () {
            self.contentImageSetUp();
          }
        });
      }
    });
    $(".visual").click(function() {
      var text = $(this).html() == "Text" ? "Visual" : "Text";
      $(this).html(text);
      tinyMCE.execCommand('mceToggleEditor', false, 'content');
    });
  },
  loadImages: function(modalTemplate) {
    $.ajax({
      url: "./?module=Ajax&action=AjaxLoadImageLibrary",
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        modalTemplate.find(".images-list .appended").remove();
        for(var i=0; i<data.length; i++) {
          var templateClone = modalTemplate.find(".images-list .template").clone();
          templateClone.removeClass('hidden').removeClass('template').addClass('appended');
          templateClone.find('.med-image').attr('data-attributes', data[i].attributes);
          templateClone.find('.med-image').attr('src', data[i].thumbnails[0]);
          templateClone.find('.med-image').attr('data-original-path', data[i].original_image);
          templateClone.find('.med-image').attr('data-media-id', data[i].id);
          templateClone.find('.med-image').attr('data-thumbnail-sizes', (data[i].thumbnail_sizes).join());
          templateClone.find('.med-image').attr('data-thumbnail-paths', (data[i].thumbnails).join());

          templateClone.find('.med-image').attr('data-global-thumbnail-paths', (data[i].global_thumbnails_path).join());
          templateClone.find('.med-image').attr('data-global-path', data[i].global_image_path);

          templateClone.find('.med-image').attr('data-original-name', data[i].name);

          templateClone.find('.med-image').attr('alt', (data[i].thumbnails).join());
          modalTemplate.find(".images-list").append(templateClone);
        }
        modalTemplate.find('.appended').click(function() {
          modalTemplate.find(".attachment").removeClass('selected');
          $(this).toggleClass('selected');

          var dataGlobalPath = $(this).find('.med-image').attr('data-global-path');

          var attributes = JSON.parse($(this).find('.med-image').attr('data-attributes'));
          var thumbnailSizes = ($(this).find('.med-image').attr('data-thumbnail-sizes')).split(',');

          var globalthumbnailPaths = ($(this).find('.med-image').attr('data-global-thumbnail-paths')).split(',');

          var options = "<option value='"+dataGlobalPath+"'>Original ("+attributes.width+"x"+attributes.height+  ")</option>";
          for (var i = 0; i < thumbnailSizes.length; i++) {
            options += "<option value='"+globalthumbnailPaths[i]+"'>"+thumbnailSizes[i]+"</option>"
          }
          modalTemplate.find("#select_image_sizes").removeClass('hidden').html(options);
        });
      }
    });
  }
}

$(document).ready(function() {
  new WIDGET.PAGE();
});

function doSort(fld, order) {
  document.searchForm.order_by.value = fld;
  document.searchForm.sort_order.value = order;
  Navigate(0);
}

$(function() {
  $('#form').areYouSure(
    {
      message: 'It looks like you have been editing something. '
             + 'If you leave before saving, your changes will be lost.'
    }
  );
