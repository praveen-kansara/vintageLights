<?php
if (!defined('__URBAN_OFFICE__')) die('Invalid Access');

$xtpl = new XTemplate("stubs/Home/Page.html");

if (!$page) {
  // TODO: do a better SEO friendly content here
  $content = "<h2 class='text-center'>Content not found<h2>";
  $xtpl->assign('CONTENT', $content);
  $xtpl->parse('main');
  $xtpl->out('main');
  exit;
}

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

require_once("include/classes/Media.php");
$objPageMedia = new PageMedia();

$xtpl->assign("BREADCRUMB", create_breadcrumb());
$page_meta = $objPageMeta->get_all_page_meta($page->id);

$banner_image = $objPageMedia->get_page_image($page->id, "banner_image");

if (!empty($banner_image) && count($banner_image) > 0) {
  if ($banner_image['at_s3']) $base_img_path = $media_path_s3;
  else $base_img_path = $media_path_site;
  $xtpl->assign('BANNER_URL', $base_img_path . $banner_image['path'] . $banner_image['name']);
} else {
  $xtpl->assign('BANNER_URL', get_default_banner_image());
}

$page->content = $focus->process_short_codes($page->content);

$page->content = media_pre_processor($page->content);
$xtpl->assign("CONTENT", $page->content);
$xtpl->assign("PAGE_TITLE", $page->title);
$xtpl->assign("PAGE_SUB_TITLE", $page->short_description);

if ($page->slug == 'about') {
  $xtpl->parse("main.mc_embed_signup");
}

$xtpl->parse('main');
$xtpl->out('main');
