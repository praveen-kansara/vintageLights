<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/Menu.php");
$focus = New Menu();


foreach($focus->column_fields as $field) {
  if(isset($_REQUEST[$field])) {
    $value = $_REQUEST[$field];
    $focus->$field = $value;
  }
}

$focus->save();

$return_url = "./?module=Menu&action=index&menu=".$focus->id;
redirect($return_url);
