<?php

if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/Meta.php");
$obj_meta = new Meta();

$id = $_REQUEST['id'];
	
if($id) {

  $page_data = $focus->retrieve($id);
  $meta_info = $obj_meta->get_page_meta_by_key($id,'short_code');
  $obj_meta->mark_deleted($meta_info['id']);
  $focus->mark_deleted($id);
}

$return_url = "./?module=Widget&action=index";
redirect($return_url);
