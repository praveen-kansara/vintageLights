var FAQ = FAQ || {};

FAQ.PORTFOLIO = function() {
  this.initialize();
}

FAQ.PORTFOLIO.prototype = {
  initialize: function() {
    this.setupBasics();
    this.slugSetUp();
    this.setUpSideBar();
    this.dropZoneSetUp();
    this.initializeTinyMCE();
  },
  copyToClipboard: function(elem) {
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
      target = elem;
      origSelectionStart = elem.selectionStart;
      origSelectionEnd = elem.selectionEnd;
    }
    else {
      target = document.getElementById(targetId);
      if (!target) {
        var target = document.createElement("textarea");
        target.style.position = "absolute";
        target.style.left = "-9999px";
        target.style.top = "0";
        target.id = targetId;
        document.body.appendChild(target);
      }
      target.textContent = elem.textContent;
    }
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    var succeed;
    try {
      succeed = document.execCommand("copy");
    }
    catch(e) {
      succeed = false;
    }
    if (currentFocus && typeof currentFocus.focus === "function") {
      currentFocus.focus();
    }
    if (isInput) {
      elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    }
    else {
      target.textContent = "";
    }
    return succeed;
  },
  bannerImageSetUp: function() {
    var self = this;
    var modalTemplate = $("#image_lib_widget");
    modalTemplate.find('.footer').html('<button id="btn_insert_image" type="button" class="_custom-button">Insert Image</button><button id="btn_close_popup" type="button" class="_custom-button">Cancel</button>');
    modalTemplate.find("#btn_insert_image").click(function() {
      var selectedImage = $(".images-list .selected .med-image");
      var imageSource = selectedImage.attr('data-original-path');
      $(".banner-image").find('.banner-img').attr('src', imageSource);
      $("input[name='banner_image']").val(selectedImage.attr('data-media-id'));
      modalTemplate.hide();
    });
    modalTemplate.find("#btn_close_popup,.close-popup").click(function() {
      modalTemplate.hide();
    });
    modalTemplate.show();
    self.loadImages(modalTemplate, true);
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
    self.loadImages(modalTemplate, true);
  },
  featuredImageSetUp: function() {
    var self = this;
    var modalTemplate = $("#image_lib_widget");
    modalTemplate.find('.footer').html('<button id="btn_insert_image" type="button" class="_custom-button">Insert Image</button><button id="btn_close_popup" type="button" class="_custom-button">Cancel</button>');
    modalTemplate.find("#btn_insert_image").click(function() {
      var selectedImage = $(".images-list .selected .med-image");
      var imageSource = selectedImage.attr('data-original-path');
      $(".preview-featured-image").find('.featured-img').attr('src', imageSource);
      $("input[name='featured_image']").val(selectedImage.attr('data-media-id'));
      modalTemplate.hide();
    });
    modalTemplate.find("#btn_close_popup,.close-popup").click(function() {
      modalTemplate.hide();
    });
    modalTemplate.show();
    self.loadImages(modalTemplate, true);
  },
  projectImageSetUp: function() {
    var self = this;
    var modalTemplate = $("#image_lib_widget");
    modalTemplate.find('.footer').html('<button id="btn_insert_image" type="button" class="_custom-button">Insert Image</button><button id="btn_close_popup" type="button" class="_custom-button">Cancel</button>');
    modalTemplate.find("#btn_insert_image").click(function() {
      var selectedImages = $(".images-list .selected .med-image");
      selectedImages.each(function(i, data) {
        var imageSource = $(this).attr('data-original-path');
        var templateClone = $(".image-lib-grid .images-list .template").clone();
        templateClone.removeClass('hidden').removeClass('template').addClass('appended');
        templateClone.find('.med-image').attr('src', imageSource);
        templateClone.find('.med-image').attr('data-media-id', $(this).attr('data-media-id'));
        $(".image-lib-grid .images-list").append(templateClone);
      });
      modalTemplate.hide();
    });
    modalTemplate.find("#btn_close_popup,.close-popup").click(function() {
      modalTemplate.hide();
    });
    modalTemplate.show();
    self.loadImages(modalTemplate, false);
  },
  slugSetUp: function () {
    var pageId = $("input[name='id']").val();
    var self = this;
    var slugBox = $("#slug_block");
    $("#title").blur(function() {
      if(!slugBox.hasClass('slug-exists') && $("#title").val()!="") {
        var title = $(this).val();
        var slug = title!="" ? self.slugify(title) : "";
        $.ajax({
          url: "./?module=Ajax&action=CheckSlug",
          type: "POST",
          data: {
            slug: slug,
            path: "testimonial",
            page_id: pageId,
            page_type: 'global_testimonial'
          },
          dataType: "json",
          error: function(data) {
            console.log(data);
          },
          success: function (data) {
            console.log(data);
            if(data.status == "ok") {
              if(data.path!="") {
                slugBox.find('span.permalink').html(data.base_url+data.path+"/<span data-slug='"+data.slug+"'>"+data.slug+"/</span>");
              }
              else {
                slugBox.find('span.permalink').html(data.base_url+data.path+"<span data-slug='"+data.slug+"'>"+data.slug+"/</span>");
              }
              slugBox.addClass("slug-exists");
              slugBox.find('p').removeClass('hidden');
              $("input[name='slug']").val(data.slug);
              $("#btn-slug-view_p").addClass('hidden');
            }
          },
          error: function(data) {
            console.log(data);
          }
        });
      }
    });
    slugBox.find(".btn-slug-edit").click(function() {
      var slugSpan = slugBox.find('span.permalink').find('span');
      slugSpan.html("<input style='height:30px;' value='"+slugSpan.attr('data-slug')+"'/>/");
      slugBox.find(".btn-slug-edit").addClass('hidden');
      slugBox.find(".btn-slug-ok").toggleClass('hidden');
      slugBox.find(".btn-slug-cancel").toggleClass('hidden');
    });
    slugBox.find(".btn-slug-ok").click(function() {
      var slugSpan = slugBox.find('span.permalink').find('span');
      var slugInput = slugBox.find('span.permalink').find('span').find('input');
      console.log(self.slugify(slugInput.val()));
      $.ajax({
        url: "./?module=Ajax&action=CheckSlug",
        type: "POST",
        data: {
          slug: self.slugify(slugInput.val()),
          path: "",
          page_id: pageId
        },
        dataType: "JSON",
        success: function (data) {
          console.log(data);
          slugSpan.attr('data-slug', data.slug);
          slugBox.find(".btn-slug-cancel").trigger('click');
          $("input[name='slug']").val(data.slug);
          $("#btn-slug-view_p").addClass('hidden');
        },
        error: function(data) {
          console.log(data);
        }
      });
    });
    slugBox.find(".btn-slug-cancel").click(function() {
      var slugSpan = slugBox.find('span.permalink').find('span');
      slugSpan.html(slugSpan.attr('data-slug')+"/");
      slugBox.find(".btn-slug-edit").toggleClass('hidden');
      slugBox.find(".btn-slug-ok").toggleClass('hidden');
      slugBox.find(".btn-slug-cancel").toggleClass('hidden');
    });
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
      previewTemplate: '<li tabindex="0" class="attachment appended"><div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait"><div class="thumbnail"><div class="centered"> <img class="med-image img-responsive" src="" alt=""> </div><div class=dz-progress><span class=dz-upload data-dz-uploadprogress=""></span></div></div></div></li>',
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
      console.log(response);
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
        $(this).toggleClass('selected');
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
      var projectImages = [];
      var projectImageContainer = $("#testimonial_images");
      var selectedImages = projectImageContainer.find('.appended .med-image');
      selectedImages.each(function(i, data) {
        projectImages.push($(this).attr('data-media-id'));
      });
      $("input[name='testimonial_images']").val(projectImages.join());
      if(tinyMCE.activeEditor.hidden) {
        tinyMCE.execCommand('mceToggleEditor', false, 'content');
      }
      $('#form').submit();
    });
    $("#btn_save_publish").click(function() {
      $("#status").val("published");
      
      var projectImages = [];
      var projectImageContainer = $("#testimonial_images");
      var selectedImages = projectImageContainer.find('.appended .med-image');
      selectedImages.each(function(i, data) {
        projectImages.push($(this).attr('data-media-id'));
      });
      $("input[name='testimonial_images']").val(projectImages.join());
      if(tinyMCE.activeEditor.hidden) {
        tinyMCE.execCommand('mceToggleEditor', false, 'content');
      }
      $('#form').submit();
    });
    $("#link_add_cat").click(function(e) {
      e.preventDefault();
      $($(this).attr('href')).toggleClass('hidden');
    });
    $("#btn_add_category").click(function(e) {
      e.preventDefault();
      var categoryName = $("#text_new_cat").val();
      $.ajax({
        url: "./?module=Category&action=Save",
        type: "POST",
        data: {
          category_name: categoryName,
          type: "ajax"
        },
        dataType: "JSON",
        success: function (data) {
          $("#master_category").append("<option selected value="+data.id+">"+categoryName+"</option>");
          $("#text_new_cat").val("");
        }
      });
    });
    $("#link_set_featured_image").click(function(e) {
      e.preventDefault();
      self.featuredImageSetUp();
    });
    $("#remove_featured_image").click(function(e) {
      e.preventDefault();
      $(".preview-featured-image").find('.featured-img').attr('src', '');
      $("input[name='featured_image']").val("");
    });
  },
  slugify: function(text) {
    return text.toString().toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^\w\-]+/g, '')
    .replace(/\-\-+/g, '-')
    .replace(/^-+/, '')
    .replace(/-+$/, '');
  },
  setUpImageLibraryPopUp: function() {
    var self = this;
    var modalTemplate = $("#image_lib_widget");
    modalTemplate.find('.footer').html('<button id="btn_insert_image" type="button" class="_custom-button">Insert Image</button>');
    modalTemplate.find('.footer').append('<button id="btn_close_popup" type="button" class="_custom-button">Cancel</button>');
    modalTemplate.find("#btn_insert_image").click(function() {
      var selectedImage = $(".images-list .selected .med-image");
      var imageSource = selectedImage.attr('src');
      self.editor.insertContent('<img src="'+imageSource+'" alt="">');
      modalTemplate.hide();
    });
    modalTemplate.find("#btn_close_popup").click(function() {
      modalTemplate.hide();
    });
    modalTemplate.show();
    self.loadImages(modalTemplate);
  },
  setupBasics: function() {
    var self = this;
    $(".btn-delete-action").click(function(e) {
      e.preventDefault();
      if (confirm("Are you sure you want to delete?") == true) {
        var deleteURL = $(this).attr('data-delete-path');
        location.href = deleteURL;
      }
    });
    $(".banner-image").click(function() {
      self.bannerImageSetUp();
    });
    $("#insert_content_image").click(function() {
      self.contentImageSetUp();
    });
    $(".attach-project-images").click(function() {
      self.projectImageSetUp();
    });
    $(".image-lib-grid").on('click', '.icon-delete-project-image', function() {
      $(this).closest('li').remove();
    });
    $(".remove-banner").click(function() {
      $('.banner-img').attr('src', '');
      $("input[name='banner_image']").val('');
    });
    $("#move_trash").click(function(e) {
      e.preventDefault();
      if (confirm("Do you really want to do it?") == true) {
        var deleteURL = "./?module="+$("input[name='module']").val()+"&action=Delete&id="+$("input[name='id']").val();
        location.href = deleteURL;
      }
    });
  },
  initializeTinyMCE: function () {
    var self = this;
    tinyMCE.init({
      selector: '#content',
      height: 250,
      relative_urls : false,
      force_br_newlines : false,
      force_p_newlines : false,
      valid_elements: '*[*]',
      forced_root_block : '',
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
      tinyMCE.execCommand('mceToggleEditor',false,'content');
    });
  },
  loadImages: function(modalTemplate, isSingle) {
    $.ajax({
      url: "./?module=Ajax&action=AjaxLoadImageLibrary",
      type: "GET",
      dataType: "JSON",
      success: function (data) {
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
          if(isSingle) {
            modalTemplate.find(".attachment").removeClass('selected');
          }
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
  new FAQ.PORTFOLIO();
});

$(function() {
  $('#form').areYouSure(
    {
      message: 'It looks like you have been editing something. '
             + 'If you leave before saving, your changes will be lost.'
    }
  );
});
