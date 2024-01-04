<?php
if(!defined('__URBAN_OFFICE__')) exit;

$xtplSearch= new XTemplate("modules/Page/Search.html");

$xtplSearch->assign('ID',   $id);
$xtplSearch->assign('NAME', $name);
$xtplSearch->assign('SEARCH_STATUS',  select_list($content_status_dom, $status, 1, "Status"));

$xtplSearch->parse('main');
$searchView = $xtplSearch->text('main');
