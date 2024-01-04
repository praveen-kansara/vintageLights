<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/Redir.php");
$focus = new Redir();

$xtpl = new XTemplate("modules/Redirection/EditView.html");

$mode = "Add";

if(isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) ) {
  $xtpl->assign('SUCCESS_MESSAGE', $_SESSION["msg"]);
  $xtpl->parse('main.msg');
  unset($_SESSION["msg"]); 
}

$xtpl->assign('REDIR_TYPE', select_list($redirection_type, $type, 1));

if(!empty($_REQUEST['id'])) {
  $focus->retrieve($_REQUEST['id']);
  $xtpl->assign('ID', $focus->id );
  $xtpl->assign('URI', $focus->uri );
  $xtpl->assign('REDIR', $focus->redir);
  $xtpl->assign('REDIR_TYPE', select_list($redirection_type, $focus->redir_type, 1));

  $mode = "Edit";
}

$xtpl->assign('MODE',  $mode);
$xtpl->parse('main');
$xtpl->out('main');
