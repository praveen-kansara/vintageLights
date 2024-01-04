<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

require_once("include/classes/PageMedia.php");
$objPageMedia = new PageMedia();

$xtpl = new XTemplate("stubs/Home/Home.html");

$home_page = $focus->get_homepage();

if($home_page) {
    
  $xtpl->assign('LOCAL_CDN_PATH', $local_cdn_image);

  $location_pages = $focus->get_location_pages();
  
  $page_meta = $objPageMeta->get_all_page_meta($home_page->id);
  
  if(isset($page_meta['home_page_video']) && !empty($page_meta['home_page_video'])) {
      $xtpl->assign('HOME_PAGE_VIDEO_SECTION', $page_meta['home_page_video']);
  }

  if(isset($page_meta['promo_band']) && !empty($page_meta['promo_band'])) {
      $xtpl->assign('PROMO_BAND', '<div class="promo">'.$page_meta['promo_band'].'</div>');
  }


  if(!empty($location_pages)) {
    foreach ($location_pages as $key => $location_details) {
      $xtpl->assign('LOCATION_NAME', $location_details->name);
      $xtpl->assign('LOCATION_URI', $location_details->uri);
      $xtpl->parse('main.location_section');
    }
  }

  $banner_image = $objPageMedia->get_page_image($home_page->id, "banner_image");

  if(!empty($banner_image) && count($banner_image)>0) {
    if($banner_image['at_s3']) $base_img_path = $media_path_s3;
    else $base_img_path = $media_path_site;
    $xtpl->assign('BANNER_URL', $base_img_path.$banner_image['path'].$banner_image['name']);
  }
  else {
    $xtpl->assign('BANNER_URL', get_default_banner_image());
  }

  $home_page_content = $home_page->content;

  $home_page_content = $focus->process_short_codes($home_page_content);

  $home_page_content = media_pre_processor($home_page_content);

  $xtpl->assign('CONTENT', $home_page_content);
} else {
  $content ="<h2 class='text-center'> Content not found <h2>";
  $xtpl->assign('CONTENT', $content );
}

$xtpl->parse('main');
$xtpl->out('main');

