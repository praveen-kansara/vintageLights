<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

require_once("include/classes/Media.php");
$objPageMedia = new PageMedia();

$xtpl = new XTemplate("stubs/Home/OurTeamDetail.html");

if($page) {
  $page_meta = $objPageMeta->get_all_page_meta($page->id);

  $banner_image = $objPageMedia->get_page_image($page->id, "banner_image");

  if(!empty($banner_image) && count($banner_image)>0) {
    if($banner_image['at_s3']) $base_img_path = $media_path_s3;
    else $base_img_path = $media_path_site;
    $xtpl->assign('BANNER_URL', $base_img_path.$banner_image['path'].$banner_image['name']);
  } else {
    $xtpl->assign('BANNER_URL', get_default_banner_image());
  }

  if(!empty($page_meta['vcard'])) $vcard_arr = json_decode($page_meta['vcard'],1);
  else $vcard_arr = array();

  if(!empty($vcard_arr)) {
    if($vcard_arr['at_s3'] == 1) {
      $vcard_path == $media_path_s3.$vcard_arr['file'];
    } else {
      $vcard_path = $media_path_site.$vcard_arr['file'];
    }
    $xtpl->assign('VCARD_PATH', $vcard_path);
    $xtpl->parse('main.download_vcard_section');
  }

  $designation_txt = isset($page_meta['designation']) ? $page_meta['designation'].'</br>' : "";
  $email_txt       = isset($page_meta['email'])       ? '<a href="'.$page_meta['email'].'" target="_blank">'.$page_meta['email'].'</a><br>'       : "";
  $phone_txt       = isset($page_meta['phone'])       ? $page_meta['phone']       : "";

  $xtpl->assign("DESIGNATION_TXT", $designation_txt);
  $xtpl->assign("EMAIL_TXT", $email_txt);
  $xtpl->assign("PHONE_TXT", $phone_txt);
  
  $page->content = $focus->process_short_codes($page->content);
  $page->content = media_pre_processor($page->content);

  $xtpl->assign("CONTENT", $page->content);
  $xtpl->assign("PAGE_TITLE", $page->title);
  $xtpl->assign("PAGE_SUB_TITLE", $page->short_description);

} else {

 $content ="<h2 class='text-center'> Content not found <h2>";

 $xtpl->assign('CONTENT',$content );

}

$xtpl->parse('main');
$xtpl->out('main');
