<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once('include/classes/Page.php');

$focus = new Page();

$id   = $_REQUEST['id'];
$type = $_REQUEST['type'];

if ( $id && $type ) {
  $focus->mark_deleted($id);
  echo true;
}
else {
  echo false;
}
