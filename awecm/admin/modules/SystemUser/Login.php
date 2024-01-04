<?php
if(!defined('__URBAN_OFFICE__')) exit;

$xtpl= new XTemplate("modules/SystemUser/Login.html");
if(isset($_GET['auth'])) {
  $xtpl->parse('main.errormessage');
}

$xtpl->assign("SITE_NAME", $site_name);
$xtpl->parse('main');
$xtpl->out('main');
