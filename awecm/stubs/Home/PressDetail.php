<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

require_once("include/classes/Media.php");
$objPageMedia = new PageMedia();

$xtpl = new XTemplate("stubs/Home/PressDetail.html");

if($page) {
  $xtpl->assign("BREADCRUMB", create_breadcrumb());
  $page_meta = $objPageMeta->get_all_page_meta($page->id);

  $featured_image = $objPageMedia->get_page_image($page->id, "featured_image");

  if(!empty($featured_image) && count($featured_image)>0) {

    $image_attr = array();
    $image_alt  = "";

    if($featured_image['at_s3']) $base_img_path = $media_path_s3;
    else $base_img_path = $media_path_site;

    if(!empty($featured_image['attributes'])) {
      $image_attr = json_decode($featured_image['attributes'],true);
      $image_alt  = isset($image_attr['alt']) ? $image_attr['alt'] : '';
    }

    $xtpl->assign('FEATURED_IMAGE_URL', $base_img_path.$featured_image['path'].$featured_image['name']);
    $xtpl->assign('FEATURED_IMAGE_ALT', $image_alt);
  } else {
    $xtpl->assign('FEATURED_IMAGE_URL', get_default_banner_image());
    $xtpl->assign('FEATURED_IMAGE_ALT', "");
  }

  $page->content = $focus->process_short_codes($page->content);
  $page->content = media_pre_processor($page->content);

  $publish_date = (!empty($page->publish_date) && $page->publish_date != '0000-00-00 00:00:00') ? date('M d, Y', strtotime($page->publish_date)) : '';

  $xtpl->assign("CONTENT", $page->content);
  $xtpl->assign("PAGE_TITLE", $page->title);
  $xtpl->assign("PAGE_SUB_TITLE", $page->short_description);
  $xtpl->assign("PUBLISH_DATE", $publish_date);

  if($page_meta['inline_scripts']) $xtpl->assign('INLINE_SCRIPTS', $page_meta['inline_scripts']);

} else {

 $content ="<h2 class='text-center'> Content not found <h2>";

 $xtpl->assign('CONTENT',$content );

}

$xtpl->parse('main');
$xtpl->out('main');
