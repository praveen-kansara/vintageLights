<?php 
if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

$xtpl = new XTemplate("stubs/Home/404_Page.html");

$xtpl->assign("SITE_URL", $site_url);
$xtpl->parse("main");
$xtpl->out("main");
