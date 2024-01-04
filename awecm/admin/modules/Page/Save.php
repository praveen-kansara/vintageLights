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
$module_type = 'page';

if(isset($_REQUEST['type']) && $_REQUEST['type']=="ajax") {
  # its a ajax request on save.php
  $is_ajax_request = true;
}

// Check this
$is_new_entry = true;

if($_REQUEST['status'] == 'published'  &&  empty($_REQUEST['id'])) { $_SESSION["msg"] = _PUBLISHED_MSG; }
elseif ($_REQUEST['status'] == 'published' && !empty($_REQUEST['id'])) { $_SESSION["msg"] = _UPDATE;     }
elseif ($_REQUEST['status'] == 'draft') { $_SESSION["msg"] = _DRAFT_MSG ; }

if( isset($_REQUEST['id']) && $_REQUEST['id']!="" ) {
  $focus->retrieve($_REQUEST['id']);
  $is_new_entry = false;
  $tag_string = $focus->tag;

  #remove old cache here
  $page_url = $focus->uri;
  if($page_url) remove_cache($page_url);

  // study how to manage tag in array structure so that we could save multiple tags using same form.
  if(isset($_REQUEST['tag'])) {
    
    if($_REQUEST['tag']!="") {
      foreach ($template_dom as $tag => $dom) {
        $pos = strpos($tag_string, $tag);
        if ($pos !== false) {
          $tag_string = build_tag_str($tag_string, $tag, 0);
        }
      }
      $_REQUEST['tag'] = build_tag_str($tag_string, $_REQUEST['tag'], 1);
    }
    else {
      foreach ($template_dom as $tag => $dom) {
        $pos = strpos($tag_string, $tag);
        if ($pos !== false) {
          $tag_string = build_tag_str($tag_string, $tag, 0);
        }
      }
      $_REQUEST['tag'] = $tag_string;
    }
  }
}

if(isset($_REQUEST['tag']) && $_REQUEST['tag'] == "home_page_template") $focus->uri = $site_url;
else if(isset($_REQUEST['slug'])) {
  $focus->uri = $site_url.$_REQUEST['slug']."/";
}

foreach($focus->column_fields as $field) {
  if(isset($_REQUEST[$field])) {
    $value = $_REQUEST[$field];
    $focus->$field = $value;
  }
}

# Fire save event
$focus->save();

#save meta keywords and meta description
$page_id = $focus->id;

$meta_robots_index  = isset($_REQUEST['meta_robots_index'])  ? $_REQUEST['meta_robots_index']  : 0;
$meta_robots_follow = isset($_REQUEST['meta_robots_follow']) ? $_REQUEST['meta_robots_follow'] : 0;
$home_page_video = isset($_REQUEST['home_page_video']) ? $_REQUEST['home_page_video'] : 0;

#Created for saving multiple values
$meta_param_dom = array(
  "meta_title"          => $_REQUEST['meta_title'],
  "meta_description"    => $_REQUEST['meta_description'],
  "meta_robots_index"   => $meta_robots_index,
  "meta_robots_follow"  => $meta_robots_follow,
  "home_page_video"     => $home_page_video,
  "promo_band"          => $_REQUEST['promo_band'],
  "property_list_secondary_content" => $_REQUEST['property_list_secondary_content'],
);

if(isset($_REQUEST['meta_keywords'])) {
  $meta_param_dom['meta_keywords'] =  $_REQUEST['meta_keywords'];
}

$meta->save_multiple_page_meta($page_id, $meta_param_dom, $module_type);

if(isset($_REQUEST['banner_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['banner_image'], 'banner_image');
}

if(isset($_REQUEST['featured_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['featured_image'], 'featured_image');
}

if(!$is_ajax_request) {
  $return_url = "./?module=Page&action=EditView&id=$page_id";
  redirect($return_url);
}
else {
  echo json_encode(['status'=>'success', 'message'=>'Updated successfully.']);
}