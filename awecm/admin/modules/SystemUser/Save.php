<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/SystemUser.php");
$focus = New SystemUser();

if(!isset($_REQUEST['is_active'])) {
  $_REQUEST['is_active'] = 0;
}

//unset post value due to custome validation
if(empty($_REQUEST['newpassword']) && $_REQUEST['id']){
  unset($_REQUEST['password']);
}

foreach($focus->column_fields as $field) {
  if(isset($_REQUEST[$field])) {
    $value = $_REQUEST[$field];
    $focus->$field = $value;
  }
}

if($_REQUEST['id']){

	if($_REQUEST['newpassword']){
		$pwd = $_REQUEST['newpassword'];
		$focus->password = md5($pwd);
	}

  $_SESSION["msg"] = "update";

} else {

  $pwd = $_REQUEST['password'];
  $focus->password = md5($pwd);
  $_SESSION["msg"] = "add";
}

$focus->save();


$return_url = "./?module=SystemUser&action=EditView&id=$focus->id";
redirect($return_url);
