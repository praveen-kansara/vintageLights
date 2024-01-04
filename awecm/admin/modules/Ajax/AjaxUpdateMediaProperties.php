<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once('include/classes/Media.php');

$focus = new Media();

if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
  $focus->retrieve($_REQUEST['id']);
  $image_meta = json_decode($focus->attributes, true);
  $image_meta['caption'] = $_REQUEST['caption'];
  $image_meta['alt'] = $_REQUEST['alt'];
  $focus->attributes = json_encode($image_meta);
  $focus->save();
  echo json_encode($_REQUEST);
}
