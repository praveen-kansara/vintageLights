<?php

if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/Page.php");
$focus = new Page();

$id = $_REQUEST['id'];

if($id) {
  $focus->mark_deleted($id);
}

$return_url = "./?module=OurTeam&action=index";
redirect($return_url);
