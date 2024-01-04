<?php
if( !defined('__URBAN_OFFICE__') ) exit;

require_once("include/classes/Page.php");
$focus = New Page();

require_once("include/classes/PageMedia.php");
$page_media_obj = New PageMedia();

require_once("include/classes/Meta.php");
$page_meta_obj = new Meta();

require_once("include/classes/Meta.php");
$meta = new Meta();

$is_ajax_request = false;
$module_type = 'faq';
$faq_path = "faq/";

if($_REQUEST['status'] == 'published'  &&  empty($_REQUEST['id'])) { $_SESSION["msg"] = _PUBLISHED_MSG; }
elseif ($_REQUEST['status'] == 'published' && !empty($_REQUEST['id'])) { $_SESSION["msg"] = _UPDATE;     }
elseif ($_REQUEST['status'] == 'draft') { $_SESSION["msg"] = _DRAFT_MSG ; }

$content = (!empty($_REQUEST['content'])) ? $_REQUEST['content'] : '';

if(isset($_REQUEST['type']) && $_REQUEST['type'] == "ajax") {
  # its a ajax request on save.php
  $is_ajax_request = true;
}

$saving_new_record = false;

if( isset($_REQUEST['id']) && $_REQUEST['id'] != "" ) {
  $focus->retrieve($_REQUEST['id']);
  #Delete old cache
  $page_url = $focus->uri;
  if($page_url) remove_cache($page_url);
} else {
  $saving_new_record = true;
}

if(!isset($_REQUEST['tag']) && !$is_ajax_request) {
  $_REQUEST['tag'] = "";
}

if(isset($_REQUEST['slug'])){
  $focus->uri = $site_url.$faq_path.$_REQUEST['slug']."/";
}

foreach($focus->column_fields as $field) {
  if(isset($_REQUEST[$field])) {
    $value = $_REQUEST[$field];
    $focus->$field = $value;
  }
}

$focus->save();

# Save meta keywords and meta description
$page_id = $focus->id;

if(!$is_ajax_request) {
  $return_url = "./?module=FAQ&action=EditView&id=$page_id";
  redirect($return_url);
}
else {
  echo json_encode(['status'=>'ok', 'message'=>'Updated successfully.']);
}
