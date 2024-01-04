<footer class="main-footer">
  <div class="pull-right hidden-xs">
  </div>
  <p class="copyright">Copyright &copy; <?php echo date('Y'); ?> <a target="_blank" 
    href="https://www.arrowebs.com/">arroWebs</a> All rights reserved.</p>
</footer>
<div class="image-library w-container" style="display:none;">
  <div class="header">
    <h2 class="headings">Image Properties</h2>
  </div>
  <div class="body">
    <div class="image-container">
      <ul class="images-list">
        <li tabindex="0" class="attachment hidden template">
            <div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">
              <div class="thumbnail">
                <div class="centered">
                  <img class="med-image img-responsive" src="" alt="">
                </div>
              </div>
            </div>
          </li>
      </ul>
    </div>
  </div>
  <div class="footer">
    <button id="btn_insert_image_media_lib" type="button" class="_custom-button">Insert Image</button>
  </div>
</div>
<div class="popup-overlay" id="image_lib_widget">
  <div class="image-lib-widget custom-popup">
  <div class="body">
    <i class="fa fa-times close-popup"></i>
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs pull-right">
        <!-- <li class=""><a href="#tab_upload_files" data-toggle="tab" aria-expanded="false">Upload Files</a></li> -->
        <li class="active"><a href="#tab_media_library" data-toggle="tab" aria-expanded="true">Media Library</a></li>
        <li class="pull-left header"> Insert Media </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_media_library">
          <div class="image-container">
            <ul class="images-list" id="popup_image_library_ul">
              <li tabindex="0" class="attachment hidden template">
                <div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">
                  <div class="thumbnail">
                    <div class="centered"> <img class="med-image img-responsive" src="" alt=""> </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="footer">
          </div>
        </div>
        <div class="tab-pane" id="tab_upload_files">
          <div id="dropzone" class="dropzone-panel">
            <form action="/upload" class="dropzone needsclick dz-clickable">
              <div class="dz-message needsclick">
                <div class="middle">
                  Drop files here or click to upload.<br>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>
<div class="popup-overlay" id="brochure_lib_widget">
  <div class="image-lib-widget custom-popup">
    <div class="body">
      <i class="fa fa-times close-popup"></i>
      <div class="modal-content">
        <form role="form" method="post" id="brochuerForm" class="form-page" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title" style="display: inline-block;">
              <i class="fa fa-file-text-o mr-3"></i>&nbsp;Add New Document</h4>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group mb-0">
                    <label for="file-upload">Upload document(s)</label>
                    <div class="upload-input">
                      <input id="file-upload" type="file" name="property_brochure" class="inputfile"/>
                      <label for="file-upload">
                        <i class="fa fa-upload" aria-hidden="true"></i>&nbsp; Select a PDF file to upload</label>
                    </div>
                  </div>
                  <label id='documentErrorLbl' for="file-upload" class="error"></label>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <input type="button" id="upload_property_brochure" class="btn btn-primary" title="Save" value="Save">
            <a href="javascript:void(0);" title="Cancel" class="btn btn-default cancel">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="popup-overlay" id="image_property_popup">
  <div class="image-lib-widget custom-popup">
  <div class="body">
    <i class="fa fa-times close-popup"></i>
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs pull-right">
        <li class="pull-left header"> Image Properties </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active">
          <div>
            <div class="image-div">
              <img class="prop-image-prev img-responsive" src="" />
            </div>
            <div class="prop-div">
              <table class="image-prop-tab">
                <tr class="hidden"><th width="40%">Image Path:</th><td width="60%"><span class="prop-image-path"></span></td></tr>
                <tr><th width="40%">Image Name:</th><td><input type="text" class="form-control prop-image-name" readonly/></td></tr>
                <tr><th width="40%">Image Caption:</th><td><input type="text" class="form-control prop-image-caption" /></td></tr>
                <tr><th width="40%">Image Alt:</th><td><input type="text" class="form-control prop-image-alt" /></td></tr>
              </table>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer">

  </div>
</div>
</div>
<div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
var image_base_url = "<?php echo $site_url.$uploaded_image_path; ?>";
</script>

<?php
echo build_script_tags($module);

if(in_array($module, array('Settings'))) { ?>
<script type="text/javascript" src="../static/javascript/magicsuggest-master/magicsuggest-min.js"></script>
<?php } ?>


<script type="text/javascript">

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
}

</script>
</body>
</html>
