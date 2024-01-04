<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/Meta.php");
$focus = new Meta();

if(isset($_REQUEST['settings'])) {
   $settings = array_combine($settings_dom, $_REQUEST['settings']);
}

if(isset($settings)) {
  foreach ($settings as $key => $setting) {
    $focus->save_page_meta(1, $key, $setting,'settings');
  }
}

$_SESSION["msg"] = "update";
$return_url = "./?module=Settings&action=EditView";
redirect($return_url);