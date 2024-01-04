var IMG_ATTACHMAENT = IMG_ATTACHMAENT || {};

IMG_ATTACHMAENT.IMAGE_ATTACHEMENTS = function() {
  this.initialize();
  this.editor = "";
}

IMG_ATTACHMAENT.IMAGE_ATTACHEMENTS.prototype = {
  initialize: function () {
    this.initializeTinyMCE();
    this.setupImageLibrary();
    this.initialSetup();
  },
  initialSetup: function() {
    var self = this;
    $('.attach-image').click(function() {
      $('.image-library').popup('show');
      if($('.images-list').children().length==1) {
        self.loadImages();
      }
    });
  },
  initializeTinyMCE: function () {
    var self = this;
    tinyMCE.init({
      selector: '#content',
      height: 250,
      menubar: false,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code'
      ],
      toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link customimage',
      content_css: '//www.tinymce.com/css/codepen.min.css',
      setup: function(editor) {
        self.editor = editor;
        editor.addButton('customimage', {
          icon: 'mce-ico mce-i-image',
          tooltip : 'insert image',
          onclick: function () {
            $('.image-library').popup('show');
            self.loadImages();
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
  loadImages: function() {
    $.ajax({
      url: "./?module=Ajax&action=AjaxLoadImageLibrary",
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $(".images-list .appended").remove();
        for(var i=0; i<data.length; i++) {
          var imageMeta = JSON.parse(data[i].meta_value);
          var templateClone = $(".images-list .template").clone();
          templateClone.removeClass('hidden').removeClass('template').addClass('appended');
          templateClone.find('.med-image').attr('src', "../"+imageMeta.thumbnails[0]);
          templateClone.find('.med-image').attr('data-page-id', data[i].id);
          $(".images-list").append(templateClone);
        }
      }
    });
  },
  setupImageLibrary: function() {
    var self = this;
    $(".images-list").on('click', '.attachment', function() {
        $(this).toggleClass('selected');
    });
    $('.image-library .image-container').slimScroll({
        height: '250px'
    });
    $("#btn_insert_image_media_lib").click(function() {
      var imageSource = $(".images-list .selected .med-image");
      $(".selected-featured-images .appended").remove();
      imageSource.each(function(i, data) {
        var template = $(".selected-featured-images .template").clone();
        template.removeClass('hidden').removeClass('template').addClass('appended').find('.preview-image').attr('src',$(this).attr('src'));
        template.find('input[name="featured_image"]').val($(this).attr('data-page-id'));
        template.find('.hid-proj-images').attr('name', "project_images[]").val($(this).attr('data-page-id'));
        $(".selected-featured-images").append(template);
      });
      $('.image-library').popup('hide');
      if($(".selected-featured-images .existing-images").length==0) {
        $(".selected-featured-images .appended").eq(0).find('input[name="featured_image"]').attr('checked',true);
      }
    });
  }
}

$(document).ready(function() {
  new IMG_ATTACHMAENT.IMAGE_ATTACHEMENTS();
});
