<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/SystemUser.php");
$focus = new SystemUser();
$xtpl  = new XTemplate("modules/SystemUser/EditView.html");

if(isset($_SESSION["msg"])) {

  if($_SESSION["msg"] == 'add'){ $xtpl->assign('SUCCESS_MESSAGE', _USER_ADD); }
  else if($_SESSION["msg"] == 'update'){ $xtpl->assign('SUCCESS_MESSAGE', _USER_UPDATE); }

  $xtpl->parse('main.msg');
  unset($_SESSION["msg"]); 
}

if(isset($_REQUEST['id'])) {
  $id = $_REQUEST['id'];
}
else {
  $id = '';
}

if($id) {
  $focus->retrieve($id);
  $xtpl->assign('DISABLE',   'disable');
  $xtpl->assign('HIDECLASS', 'hide');
}
else {
  $xtpl->assign('DISABLE2','disable');
  $xtpl->assign('HIDE','hide');
}

$id ? $xtpl->assign('HEADLABLE', 'Edit') : $xtpl->assign('HEADLABLE', 'Add');
$checked_status = "";
$focus->is_active == 1 ? $checked_status = "checked" : $checked_status = '';

$xtpl->assign('ID',   	 $focus->id);
$xtpl->assign('NAME',	   $focus->name);
$xtpl->assign('EMAIL',	 $focus->email);
$xtpl->assign('PHONE',	 $focus->phone);
$xtpl->assign('CITY',	   $focus->city);
$xtpl->assign('ADDRESS', $focus->address);
$xtpl->assign('STATUS',	 $checked_status);

if($id) $xtpl->parse('main.delete_btn');

$xtpl->parse('main');
$xtpl->out('main');
