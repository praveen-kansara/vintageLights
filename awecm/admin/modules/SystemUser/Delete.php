<?php

if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/SystemUser.php");
$focus = new SystemUser();

$id    = $_REQUEST['id'];

if($id){
  
  $focus->retrieve($id);

  if($focus->id != $current_user_id){
    $focus->mark_deleted($id);
  } 

}

$return_url = "./?module=SystemUser&action=index";
redirect($return_url);
