var URBAN_OFFICE = URBAN_OFFICE || {};

URBAN_OFFICE.IMAGE_LIB = function() {
  this.initialize();
  this.editor = "";
}

URBAN_OFFICE.IMAGE_LIB.prototype = {
  initialize: function () {
    this.initializeTinyMCE();
    this.setupImageLibrary();
  },
  initializeTinyMCE: function () {
    var self = this;
    tinyMCE.init({
      selector: '#page_description',
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
      tinyMCE.execCommand('mceToggleEditor',false,'page_description');
    });
  },
  loadImages: function() {
    $.ajax({
      url: "./?q=Ajax/AjaxLoadImageLibrary",
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $(".images-list .appended").remove();
        for(var i=0; i<data.length; i++) {
          var templateClone = $(".images-list .template").clone();
          templateClone.removeClass('hidden').removeClass('template').addClass('appended');
          templateClone.find('.med-image').attr('src',data[i].path);
          $(".images-list").append(templateClone);
        }
      }
    });
  },
  setupImageLibrary: function() {
    var self = this;
    $(".images-list").on('click', '.attachment', function() {
        $(".images-list .attachment").removeClass('selected');
        $(this).toggleClass('selected');
    });
    $('.image-library .image-container').slimScroll({
        height: '250px'
    });
    $("#btn_insert_image_media_lib").click(function() {
      var imageSource = $(".images-list .selected .med-image").attr('src');
      self.editor.insertContent('<img src="'+imageSource+'" draggable="false" alt="">');
      $('.image-library').popup('hide');
    });
  }
}

$(document).ready(function() {
  new URBAN_OFFICE.IMAGE_LIB();
});
