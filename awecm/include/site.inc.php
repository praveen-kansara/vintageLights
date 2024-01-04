<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("classes/Meta.php");
$focus = new Meta();

$site =  $focus->get_all_page_meta(1);
$site = (object) $site;
