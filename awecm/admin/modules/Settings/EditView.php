<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/Meta.php");
$focus = new Meta();

$xtpl  = new XTemplate("modules/Settings/EditView.html");

if(isset($_SESSION["msg"]) && $_SESSION["msg"] == 'update') {
  $xtpl->assign('SUCCESS_MESSAGE', _USER_UPDATE);
  $xtpl->parse('main.msg');
  unset($_SESSION["msg"]); 
}

$page_meta_array =  $focus->get_all_page_meta(1);

$xtpl->assign('NAME',  isset($page_meta_array['site_name']) ? $page_meta_array['site_name'] : "");
$xtpl->assign('EMAIL', isset($page_meta_array['site_email'])    ? $page_meta_array['site_email'] : "");
$xtpl->assign('HEADER_SCRIPT', isset($page_meta_array['header_script']) ? $page_meta_array['header_script'] : "");
$xtpl->assign('FOOTER_SCRIPT', isset($page_meta_array['footer_script']) ? $page_meta_array['footer_script'] : "");

if(isset($page_meta_array['page_settings'])) {
  $page_settings = json_decode($page_meta_array['page_settings'],true);
}

if(isset($page_meta_array['email_settings'])) {
  $email_settings = json_decode($page_meta_array['email_settings'],true);
}

# For Email Settings
// if(!empty($email_settings_dom)) {
//   $em_count = 1;
//   foreach ($email_settings_dom as $email_setting_key => $email_setting_val) {

//     if($em_count%2 == 1) {
//       $xtpl->parse("main.email_settings_dom.email_setting_section.em_row_start");
//     }

//     $email_sett_val = '';
//     $xtpl->assign("EMAIL_SETTING_KEY",       $email_setting_key);
//     $xtpl->assign("EMAIL_SETTING_LABEL",     $email_setting_val['label']);
//     $xtpl->assign("EMAIL_SETTING_PLACEHOLD", $email_setting_val['placeholder']);
//     $xtpl->assign("EMAIL_SETTING_TYPE",      $email_setting_val['type']);

//     if(isset($email_settings[$email_setting_key])) {
//       $email_sett_val = $email_settings[$email_setting_key];
//     }
//     $xtpl->assign("EMAIL_SETTING_VAL", $email_sett_val);

//     if($em_count%2 == 0) {
//       $xtpl->parse("main.email_settings_dom.email_setting_section.em_row_end");
//     }

//     $em_count++;

//     $xtpl->parse("main.email_settings_dom.email_setting_section");
//   }

//   //Ensure there is no open div if the number of elements not a multiple of 2
//   if ($em_count%2 != 1) {
//     $xtpl->assign("EM_CLOSE_DIV","</div>");
//   }
//   $xtpl->parse("main.email_settings_dom");
// }

$xtpl->parse('main');
$xtpl->out('main');

