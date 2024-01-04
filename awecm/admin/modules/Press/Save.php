<?php
#Purpose: To save the page information

if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/Page.php");
$focus = New Page();

require_once("include/classes/PageMedia.php");
$page_media_obj = New PageMedia();

require_once("include/classes/Meta.php");
$meta = new Meta();

$is_ajax_request = false;
$module_type = 'press';

if(isset($_REQUEST['type']) && $_REQUEST['type']=="ajax") {
  # its a ajax request on save.php
  $is_ajax_request = true;
}

// Check this
$is_new_entry = true;

if($_REQUEST['status'] == 'published'  &&  empty($_REQUEST['id'])) { $_SESSION["msg"] = _PUBLISHED_MSG; }
elseif ($_REQUEST['status'] == 'published' && !empty($_REQUEST['id'])) { $_SESSION["msg"] = _UPDATE;     }
elseif ($_REQUEST['status'] == 'draft') { $_SESSION["msg"] = _DRAFT_MSG ; }

if(isset($_REQUEST['id']) && $_REQUEST['id']!="" ) {
  $focus->retrieve($_REQUEST['id']);
  $is_new_entry = false;
  $tag_string = $focus->tag;

  #remove old cache here
  $page_url = $focus->uri;
  if($page_url) remove_cache($page_url);

  // study how to manage tag in array structure so that we could save multiple tags using same form.
  if(!isset($_REQUEST['tag'])) {
    $_REQUEST['tag'] = 'press_detail_template';
  }
}

if(isset($_REQUEST['slug'])){
  $focus->uri = $site_url.$press_path.$_REQUEST['slug']."/";
}


foreach($focus->column_fields as $field) {
  if(isset($_REQUEST[$field])) {
    $value = $_REQUEST[$field];
    $focus->$field = $value;
  }
}

# Save publish date
if(!empty($_REQUEST['publish_date']) && ($_REQUEST['status'] == 'published') ) {
  $publish_date = date('Y-m-d H:i:s', strtotime($_REQUEST['publish_date']));
  $focus->publish_date = $publish_date;
} else if($_REQUEST['status'] == 'draft') 
  $focus->publish_date = NULL;

# Fire save event
$focus->save();

#save meta keywords and meta description
$page_id = $focus->id;

$meta_robots_index  = isset($_REQUEST['meta_robots_index'])  ? $_REQUEST['meta_robots_index']  : 0;
$meta_robots_follow = isset($_REQUEST['meta_robots_follow']) ? $_REQUEST['meta_robots_follow'] : 0;

#Created for saving multiple values
$meta_param_dom = array(
  "meta_title"          => $_REQUEST['meta_title'],
  "meta_description"    => $_REQUEST['meta_description'],
  "meta_robots_index"   => $meta_robots_index,
  "meta_robots_follow"  => $meta_robots_follow,
);

if(isset($_REQUEST['meta_keywords'])) {
  $meta_param_dom['meta_keywords'] =  $_REQUEST['meta_keywords'];
}

if(isset($_REQUEST['inline_scripts'])) {
  $meta_param_dom['inline_scripts'] =  $_REQUEST['inline_scripts'];
}

$meta->save_multiple_page_meta($page_id, $meta_param_dom, $module_type);

if(isset($_REQUEST['banner_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['banner_image'], 'banner_image');
}

if(isset($_REQUEST['featured_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['featured_image'], 'featured_image');
}

if(!$is_ajax_request) {
  $return_url = "./?module=Press&action=EditView&id=$page_id";
  redirect($return_url);
}
else {
  echo json_encode(['status'=>'success', 'message'=>'Updated successfully.']);
}