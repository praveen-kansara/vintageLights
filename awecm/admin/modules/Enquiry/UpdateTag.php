<?php

if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/Enquiry.php");
$focus = new Enquiry();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

if($id) {

  $focus->retrieve($id);

  if($focus->id != $current_user_id) {
    $focus->tag = build_tag_str($focus->tag, 'is_closed', 1);

    $focus->save();
  }

}

$return_url = "./?module=Enquiry&action=index";
redirect($return_url);
