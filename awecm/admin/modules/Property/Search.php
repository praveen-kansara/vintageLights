<?php
if( !defined('__URBAN_OFFICE__') ) exit;

$xtplSearch = new XTemplate("modules/Property/Search.html");

$xtplSearch->assign('NAME', $name);
$xtplSearch->assign('SEARCH_STATUS', select_list($content_status_dom, $status, 1, "Status"));
$xtplSearch->assign('SEARCH_LOCATION', select_list($location_dom, $location, 1, "Location"));

$xtplSearch->parse('main');
$searchView = $xtplSearch->text('main');
