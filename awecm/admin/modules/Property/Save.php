<?php

#Purpose: To save the property information

if( !defined('__URBAN_OFFICE__') ) exit;

require_once("include/classes/Page.php");
$focus = New Page();

require_once("include/classes/PageMedia.php");
$page_media_obj = New PageMedia();

require_once("include/classes/Meta.php");
$meta = new Meta();

$is_ajax_request = false;
$module_type = 'property';

if($_REQUEST['status'] == 'published'  &&  empty($_REQUEST['id'])) $_SESSION["msg"] = _PUBLISHED_MSG;
else if ($_REQUEST['status'] == 'published' && !empty($_REQUEST['id'])) $_SESSION["msg"] = _UPDATE;
else if ($_REQUEST['status'] == 'draft') $_SESSION["msg"] = _DRAFT_MSG;

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
  $focus->uri = $site_url.$property_path.$_REQUEST['slug']."/";
}

foreach($focus->column_fields as $field) {
  if(isset($_REQUEST[$field])) {
    $value = $_REQUEST[$field];
    $focus->$field = $value;
  }
}

$property_city = isset($_REQUEST['property_city']) ? $_REQUEST['property_city'] : '';

$focus->location = $property_city;
$focus->save();

#save meta keywords and meta description
$page_id = $focus->id;

$meta_robots_index  = isset($_REQUEST['meta_robots_index'])  ? $_REQUEST['meta_robots_index']  : 0;
$meta_robots_follow = isset($_REQUEST['meta_robots_follow']) ? $_REQUEST['meta_robots_follow'] : 0;
$property_amenities = isset($_REQUEST['property_amenities']) ? $_REQUEST['property_amenities'] : array();
$property_address   = isset($_REQUEST['property_address'])   ? $_REQUEST['property_address']   : '';
$property_state     = isset($_REQUEST['property_state'])     ? $_REQUEST['property_state']     : '';
$property_zip       = isset($_REQUEST['property_zip'])       ? $_REQUEST['property_zip']       : '';
$property_lat       = isset($_REQUEST['property_lat'])       ? $_REQUEST['property_lat']       : '';
$property_long      = isset($_REQUEST['property_long'])      ? $_REQUEST['property_long']      : '';
$property_video     = isset($_REQUEST['property_video'])     ? $_REQUEST['property_video']     : '';
$walkable_aminity   = isset($_REQUEST['walkable_aminity'])   ? $_REQUEST['walkable_aminity']   : '';
$onsite_aminity     = isset($_REQUEST['onsite_aminity'])     ? $_REQUEST['onsite_aminity']     : '';
$calendly_url       = isset($_REQUEST['calendly_url'])       ? $_REQUEST['calendly_url']       : '';

$property_location_code = isset($_REQUEST['property_location_code']) ? $_REQUEST['property_location_code'] : '';

$matterport_url = isset($_REQUEST['matterport_url']) ? $_REQUEST['matterport_url'] : '';

$matterport_url2 = isset($_REQUEST['matterport_url2']) ? $_REQUEST['matterport_url2'] : '';

$floor_plan_title     = isset($_REQUEST['floor_plan_title'])     ? $_REQUEST['floor_plan_title']     : '';

$floor_plan_sub_title = isset($_REQUEST['floor_plan_sub_title']) ? $_REQUEST['floor_plan_sub_title'] : '';

$floor_plan2_title     = isset($_REQUEST['floor_plan2_title'])     ? $_REQUEST['floor_plan2_title']     : '';

$floor_plan3_title     = isset($_REQUEST['floor_plan3_title'])     ? $_REQUEST['floor_plan3_title']  : '';


#Created for saving multiple values
$meta_param_dom = array(
  "meta_title"             => $_REQUEST['meta_title'],
  "meta_description"       => $_REQUEST['meta_description'],
  "meta_robots_index"      => $meta_robots_index,
  "meta_robots_follow"     => $meta_robots_follow,
  "property_amenities"     => json_encode($property_amenities),
  "property_address"       => $property_address,
  "property_city"          => $property_city,
  "property_state"         => $property_state,
  "property_zip"           => $property_zip,
  "property_lat"           => $property_lat,
  "property_long"          => $property_long,
  "property_video"         => $property_video,
  "floor_plan_title"       => $floor_plan_title,
  "floor_plan_sub_title"   => $floor_plan_sub_title,
  "floor_plan2_title"      => $floor_plan2_title,
  "floor_plan3_title"      => $floor_plan3_title,
  "walkable_aminity"       => $walkable_aminity,
  "onsite_aminity"         => $onsite_aminity,
  "property_location_code" => $property_location_code,
  "matterport_url"         => $matterport_url,
  "matterport_url2"        => $matterport_url2,
  "calendly_url"           => $calendly_url,
);

if(isset($_REQUEST['meta_keywords'])) {
  $meta_param_dom['meta_keywords'] =  $_REQUEST['meta_keywords'];
}

#-----------------------------------------------
# START:  Save property brochur
# Note: We've managed an hidden input 'pdf_path'
#-----------------------------------------------
$meta_param_dom['property_brochure'] = "";

if ($_FILES['brochure']['name']) {

  $file_name = prettify_image_name($_FILES['brochure']['name']);

  # Getting extension of uploaded file
  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

  if(in_array(strtolower($file_extension), array('pdf'))) {

    $base_file_name = basename($file_name, ".".pathinfo($file_name, PATHINFO_EXTENSION));

    #Creating directories in file path directory
    $path = "../".trim($uploaded_image_path);
    $md5  = md5($base_file_name);

    for($i = 0; $i < 6; $i++) {
      $path = $path . $md5{$i} . '/';
      if(!is_dir($path)) {
        @mkdir($path);
        @chmod($path, 0777);
      }
    }

    $full_path = $path.$base_file_name.'.'.$file_extension;
    $path_only = str_replace("../media/","",$path);

    if (move_uploaded_file($_FILES['brochure']['tmp_name'], $full_path)) {
      $file_res = array(
        'at_s3' => '0',
        'file'  => $path_only.$file_name
      );
      $meta_param_dom['property_brochure'] = json_encode($file_res);
    }
  }
} else {

  if (isset($_REQUEST['pdf_path']) && !empty($_REQUEST['pdf_path'])) {
    $file_res = array(
      'at_s3' => '0',
      'file'  => $_REQUEST['pdf_path']
    );
    $meta_param_dom['property_brochure'] = json_encode($file_res);
  }
}
#------------------------------
# END:  Save property brochure
#------------------------------

$meta->save_multiple_page_meta($page_id, $meta_param_dom, $module_type);

#save barnner second banner, floor_plan and feature images
if(isset($_REQUEST['banner_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['banner_image'], 'banner_image');
}

if(isset($_REQUEST['second_banner_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['second_banner_image'], 'second_banner_image');
}

if(isset($_REQUEST['floor_plan_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['floor_plan_image'], 'floor_plan_image');
}

if(isset($_REQUEST['floor_plan_zoom_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['floor_plan_zoom_image'], 'floor_plan_zoom_image');
}



if(isset($_REQUEST['floor_plan2_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['floor_plan2_image'], 'floor_plan2_image');
}

if(isset($_REQUEST['floor_plan2_zoom_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['floor_plan2_zoom_image'], 'floor_plan2_zoom_image');
}



if(isset($_REQUEST['floor_plan3_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['floor_plan3_image'], 'floor_plan3_image');
}

if(isset($_REQUEST['floor_plan3_zoom_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['floor_plan3_zoom_image'], 'floor_plan3_zoom_image');
}


if(isset($_REQUEST['featured_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['featured_image'], 'featured_image');
}

if(isset($_REQUEST['video_placeholder_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['video_placeholder_image'], 'video_placeholder_image');
}


//echo "<pre>".print_R($_REQUEST['property_images']);die;

if(isset($_REQUEST['property_images'])) {
  $multiple_images = explode(",", $_REQUEST['property_images']);
  $page_media_obj->save_multi_image($focus->id, $multiple_images, 'property_image');
}

if(!$is_ajax_request) {
  $return_url = "./?module=Property&action=EditView&id=$page_id";
  redirect($return_url);
} else {
  echo json_encode(['status'=>'ok', 'message'=>'Updated successfully.']);
}
