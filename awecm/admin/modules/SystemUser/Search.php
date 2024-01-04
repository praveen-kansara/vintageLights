<?php
if(!defined('__URBAN_OFFICE__')) exit;

$xtplSearch = new XTemplate("modules/SystemUser/Search.html");
$xtplSearch->assign('NAME',  $name);
$xtplSearch->assign('EMAIL', $email);
$xtplSearch->assign('STATUS', select_list($customer_status_dom, $is_active, 1, "Status"));  

$xtplSearch->parse('main');
$searchView = $xtplSearch->text('main');
