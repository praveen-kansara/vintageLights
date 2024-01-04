<?php
if(!defined('__URBAN_OFFICE__')) exit;

$xtplSearch = new XTemplate("modules/Redirection/Search.html");

$xtplSearch->assign('URI', $uri);

$xtplSearch->parse('main');
$searchView = $xtplSearch->text('main');
