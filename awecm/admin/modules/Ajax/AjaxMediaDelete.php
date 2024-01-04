<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once('include/classes/Media.php');

$focus = new Media();

if(isset($_REQUEST['id']) && $_REQUEST['id']!="") {
  $id = $_REQUEST['id'];
  $focus->mark_deleted($id);
  echo json_encode(['status'=>'ok', 'msg'=> "Successfully moved to trash"]);
}
