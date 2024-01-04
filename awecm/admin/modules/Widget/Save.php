<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/Page.php");
$focus = New Page();

require_once("include/classes/Meta.php");
$meta = new Meta();

$is_ajax_request = false;

if($_REQUEST['status'] == 'published')  { $_SESSION["msg"] = "published"; }
elseif ($_REQUEST['status'] == 'draft') { $_SESSION["msg"] = "draft";     }

if(isset($_REQUEST['type']) && $_REQUEST['type']=="ajax") {
  # its a ajax request on save.php
  $is_ajax_request = true;
}

// Check this
$is_new_entry = true; 

if( isset($_REQUEST['id']) && $_REQUEST['id']!="" ) {
  
  $focus->retrieve($_REQUEST['id']);
  $is_new_entry = false;

}

foreach($focus->column_fields as $field) {
  if(isset($_REQUEST[$field])) {
    $value = $_REQUEST[$field];
    $focus->$field = $value;
  }
}

$focus->save();

#save meta keywords and meta description
$page_id = $focus->id;

$module_type = 'widget';

if(isset($_REQUEST['short_code'])) {
  $meta->save_page_meta($page_id, 'short_code', $_REQUEST['short_code'], $module_type);
}

if(!$is_ajax_request) {
  $return_url = "./?module=Widget&action=EditView&id=$page_id";
  redirect($return_url);
}
else {
  echo json_encode(['status'=>'success', 'message'=>'Updated successfully.']);
}
