<?php
if(!defined('__URBAN_OFFICE__')) exit;

$xtplSearch = new XTemplate("modules/Enquiry/Search.html");

$to_date   = str_replace("23:59:59", "", $todate);
$from_date = str_replace("00:00:00", "", $fromdate);

$xtplSearch->assign('FROMDATE', $from_date);
$xtplSearch->assign('TODATE', $to_date);
$xtplSearch->assign('NAME_EMAIL_VAL', $name_email_val);
$xtplSearch->assign('ENQUIRY_TYPE',  select_list($enquiry_dom, $enquiry_type,1,"Inquiry Type"));

$xtplSearch->parse('main');
$searchView = $xtplSearch->text('main');
