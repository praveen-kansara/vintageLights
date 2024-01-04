<?php
if (!defined('__URBAN_OFFICE__')) exit;

# Purpose: Save the redirection url
require_once("include/classes/Redir.php");
$focus = new Redir();

$_SESSION["msg"] = _USER_ADD;

if($_REQUEST['id']){ $_SESSION["msg"] = _UPDATE; } 

foreach($focus->column_fields as $field) {
  if(isset($_REQUEST[$field])) {
    $value = $_REQUEST[$field];
    $focus->$field = $value;
  }
}

$focus->save();

# save the updated data in JSON file
create_rdir_json();

$id = $focus->id;

$return_url = "./?module=Redirection&action=EditView&id=$id";
redirect($return_url);