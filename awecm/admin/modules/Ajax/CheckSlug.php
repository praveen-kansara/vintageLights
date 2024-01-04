<?php

if(!defined('__URBAN_OFFICE__')) exit;

require_once('include/classes/Page.php');
$focus = new Page();

$sub_url = "";

$page_id = isset($_REQUEST['page_id']) ? $_REQUEST['page_id'] : '';

if(isset($_REQUEST['slug'])) {

  if($_REQUEST['page_id']!="") {
    $slug = get_slug($_REQUEST['slug'], $_REQUEST['page_id']);
  }
  else {
    $slug = get_slug($_REQUEST['slug']);
  }

if(isset($_REQUEST['page_type'])) {

	if($_REQUEST['page_type'] == 'property') { 
    $sub_url = $property_path; 
  } else if($_REQUEST['page_type'] == 'press') {
    $sub_url = $press_path; 
  } else if($_REQUEST['page_type'] == 'blog') {
    $sub_url = $blog_path; 
  }
}

  echo json_encode(['status'=>'ok','msg'=>'slug available', 'base_url'=>$site_url.$sub_url, "path"=>$_REQUEST['path'], "slug" => $slug]);
}
