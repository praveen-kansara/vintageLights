<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/PageMedia.php");
$focus = new PageMedia();

// if(isset($_REQUEST['id']) && $_REQUEST['id'] !="" ) {
//   $page_id = $_REQUEST['id'];
//   $media_ids = $focus->get_image_ids_by_page_id($page_id);
//   echo json_encode($media_ids);
// }

$page_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$name    = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';

if($page_id != '' || $name != '') {

  $media_ids = $focus->get_image_ids_by_page_id($page_id, $name);
  echo json_encode($media_ids);
}
