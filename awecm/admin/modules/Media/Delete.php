<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/Page.php");
$page_obj = new Page();

require_once("include/classes/Meta.php");
$page_meta_obj = new Meta();


if(isset($_REQUEST['id'])) {
    
  $row_id = $_REQUEST['id'];
  $path = "../".$page_meta_obj->get_image_via_page_id($row_id);

  // # Deleting resized image
  $image_name   = pathinfo($path);
  $newfile_path = str_replace($image_name['filename'], $image_name['filename'].'-200x200',$path);

  if(file_exists($path)) {
    unlink($path);  
  }
  if(file_exists($newfile_path)) {
    unlink($newfile_path);
  }
  $page_obj->mark_deleted($row_id);

  echo json_encode(['status'=>'ok', 'msg'=> 'Deleted successfully', 'image_path'=>$path]);

} else {
  echo json_encode(['status'=>'error', 'msg'=> 'Error in deleting image']);
}


