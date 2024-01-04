<?php

if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');
# Enquiry delete

require_once("include/classes/Enquiry.php");
$focus = new Enquiry();

$id = $_REQUEST['id'];

if($id) {

  $focus->retrieve($id);

  if($focus->id != $current_user_id) {
    $focus->mark_deleted($id);
  }

}

$return_url = "./?module=Enquiry&action=index";
redirect($return_url);
