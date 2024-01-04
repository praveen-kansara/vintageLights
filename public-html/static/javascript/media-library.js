var URBAN_OFFICE = URBAN_OFFICE || {};

URBAN_OFFICE.IMAGE_LIB = function() {
  this.initialize();
}

URBAN_OFFICE.IMAGE_LIB.prototype = {
  initialize: function () {
    this.setupImagePropertyPopup();
    this.populateImageDetailsPopUp();
    this.setUpDropZone();
    this.basicSetUp();
  },
  basicSetUp: function() {
    $("#btn_reset").click(function() {
      $('.select2').val(null).trigger('change');
      $("#media_name").val(null).trigger("change");
      $(".lib-list").html('');
      get_images();
    });
    var button_filter = $("#btn_filter");
    button_filter.click(function() {

      if($("#filters").val() != '' || $("#media_name").val() != '') {
        $.ajax({
          type: 'GET',
          dataType: "json",
          url: "./?module=Ajax&action=AjaxImageFilterResults",
          data: { id : $("#filters").val(), name: $("#media_name").val() },
          beforeSend: function() {
            button_filter.html('Searching...');
          },
          success: function(resultData) {
            var filteredImageIds = [];
            for (var i = 0; i < resultData.length; i++) {
              filteredImageIds.push(resultData[i].media_id);
            }
            $("#lib_list li").each(function() {
              var media_id = $(this).find('.images-pop-anchor').attr('data-page-id');
              if(filteredImageIds.indexOf(media_id)!=-1) {
                $(this).removeClass('hidden');
              }
              else {
                $(this).addClass('hidden');
              }
            });
            button_filter.html('Search');
          },
          error: function(data) {
            console.log(data);
          }
        });
      }
    });
  },
  setUpDropZone: function() {
    if($("#add_media_image").length) {
      Dropzone.autoDiscover = false;
      var myDropzone = new Dropzone('#add_media_image', {
        url: "./?module=Media&action=Save",
        thumbnailWidth: 200,
        thumbnailHeight: 200,
        parallelUploads: 20,
        addRemoveLinks: false,
        maxFiles: 20,
        maxFilesize: 10,
        autoDiscover: false,
        autoProcessQueue: false,
        previewTemplate: '<li class="lib-list"><a class="images-pop-anchor" href="javascript:;"><div class="dz-complete dz-image-preview dz-preview dz-processing dz-success media-item"><div class=media-wrapper><div class=media-preview><div id="media_image" class=dz-details><img class="img-responsive main-image" src={row.path} alt=s-l300.jpg data-dz-thumbnail=images/download.png></div><div class=dz-progress><span class=dz-upload data-dz-uploadprogress=""></span></div></div></div></div></a></li>',
        autoQueue: true,
        previewsContainer: "#lib_list",
        clickable: "#add_media_image"
      });
      myDropzone.on("thumbnail", function(file) {
        setTimeout(function() {
          myDropzone.processQueue();
        }, 2000);
      });
      myDropzone.on("success", function(file,response) {
        var server_response = JSON.parse(response);
        $(file.previewElement).find('.images-pop-anchor').attr('data-page-id', server_response.id);
        $(file.previewElement).find('.dz-progress').hide();
        $.bootstrapGrowl("Uploaded successfully", { type: 'success' });
      });

    }
  },
  setupImagePropertyPopup: function() {
    var self = this;
    $(".images-list").on('click', '.attachment', function() {
      $(this).toggleClass('selected');
    });
    $("#btn_insert_image_media_lib").click(function() {
      var imageSource = $(".images-list .selected .med-image").attr('src');
      self.editor.insertContent('<img src="'+imageSource+'" draggable="false" alt="">');
      $('.image-library').popup('hide');
    });
  },
  populateImageDetailsPopUp: function() {
    var self = this;
    $("#lib_list").on('click', ".images-pop-anchor", function(e) {
      e.preventDefault();
      var pageId = $(this).attr('data-page-id');
      $.ajax({
        type: 'GET',
        dataType: "json",
        url: "./?module=Ajax&action=AjaxImageDetail",
        data: { id : pageId },
        success: function(resultData) {
          console.log(resultData);
          var imageProperties = JSON.parse(resultData.attributes);
          var modalTemplate = $("#image_property_popup");
          modalTemplate.show();
          modalTemplate.find('.footer').html('<button id="btn_update_image_prop" type="button" class="_custom-button">Update</button><button id="btn_cancel_prop_update" type="button" class="_custom-button">Cancel</button><a href="" class="text-danger" id="anc_move_trash_image"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Move To Trash</a>');
          modalTemplate.find('.prop-image-path').html(resultData.original_image);
          modalTemplate.find('.prop-image-name').val(resultData.image_name);
          modalTemplate.find('.prop-image-caption').val(imageProperties.caption);
          modalTemplate.find('.prop-image-alt').val(imageProperties.alt);
          modalTemplate.find('.prop-image-prev').attr('src', resultData.thumbnails[0]);
          modalTemplate.find("#btn_cancel_prop_update, .close-popup").click(function() {
            modalTemplate.hide();
          });
          modalTemplate.find("#btn_update_image_prop").click(function() {
            self.updateImageProperties(resultData.id, modalTemplate.find('.prop-image-caption').val(), modalTemplate.find('.prop-image-alt').val(), modalTemplate);
          });
          modalTemplate.find("#anc_move_trash_image").click(function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete?") == true) {
              self.deleteImage(resultData.id, modalTemplate);
            }
          });
        },
        error: function(data) {
          console.log(data);
        }
      });
    });
  },
  updateImageProperties: function(mediaId, caption, alt, modalTemplate) {
    $.ajax({
      type: 'POST',
      dataType: "JSON",
      url: "./?module=Ajax&action=AjaxUpdateMediaProperties",
      data: {
        id:mediaId,
        caption:caption,
        alt:alt
      },
      success: function(resultData) {
        console.log(resultData);
        modalTemplate.hide();
        $.bootstrapGrowl("Properties saved successfully.", { type: 'success' });
      }
    });
  },
  deleteImage: function(mediaId, modalTemplate) {
    console.log(mediaId);
    $.ajax({
      type: 'POST',
      dataType: "JSON",
      url: "./?module=Ajax&action=AjaxMediaDelete",
      data: {
        id:mediaId
      },
      success: function(resultData) {
        console.log(resultData);
        modalTemplate.hide();
        $.bootstrapGrowl(resultData.msg, { type: 'success' });
        $("a[data-page-id='"+mediaId+"']").closest('li').remove();
      }
    });
  }
}

$(document).ready(function() {
  new URBAN_OFFICE.IMAGE_LIB();
});

function get_images(media_name, page_filter) {

  var page_no = $('.load_more').data("pageno");

   $.ajax({
     type: 'POST',
     dataType: "html",
     url: "./?module=Ajax&action=AjaxImageListView",
     data: {page_no: page_no,media_name:media_name, page_filter:page_filter},
     beforeSend: function() {
       $(".page_par").html('');
       $(".loader_par").show();
       $(".lib-list").append('<div class="media-page-loader"></div>');
     },
     success: function(html) {
      $(".media-page-loader").remove();
      if(media_name != '' || page_filter != '') {
        $(".lib-list").html('');
      }
       $(".load_more_paging").remove();
       $(".lib-list").append(html);
     }
   });

}

$(document).ready(function() {
   get_images();
  $("#search_filter").on('click', function() {

    var media_name = $('#media_name').val();
    var page_filter = $('#filters').val();

    if(media_name != '' || page_filter !='') {
      $(".lib-list").html('');
      get_images(media_name, page_filter);
    }
  })

});
