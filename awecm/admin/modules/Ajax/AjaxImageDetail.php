<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/Media.php");
$focus = new Media();

if(isset($_REQUEST['id']) && $_REQUEST['id'] !="" ) {

  $media_id = $_REQUEST['id'];
  $focus->retrieve($media_id);
  $original_image = get_image_absolute_path($focus->path, $focus->name);
  $thumbnails = get_all_thumbnails_path($focus->path, $focus->name);
  echo json_encode([
    'image_name'=>$focus->name,
    'original_image'=> $original_image,
    'attributes'=>$focus->attributes,
    'id'=>$focus->id,
    'thumbnails'=>$thumbnails
  ]);
  
}
