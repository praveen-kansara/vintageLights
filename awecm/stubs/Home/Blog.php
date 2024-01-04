<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

require_once("include/classes/Page.php");
$obj_page = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

require_once("include/classes/Media.php");
$objPageMedia = new PageMedia();

$xtpl = new XTemplate("stubs/Home/Blog.html");

$where = array();
$where[] = "post_type = 'blog'";
$where[] = "deleted = false";
$where[] = "status = 'published'";

$where_cls = build_where_clause($where);
$query = $obj_page->generate_list_query($where_cls, 'publish_date DESC');
$response = $focus->get_list_by_query($query);

$xtpl->assign("BREADCRUMB", create_breadcrumb());

$cnt = 1;

if($response['list']) {
  foreach($response['list'] as $seed) {
    $row['cls'] = '';

    $row = $seed->get_list_view_data();

    if($cnt%2 == 0) $row['cls'] = 'bg-grey';
    
    $row['title_txt'] = $row['title'];
  	
    $featured_image = $objPageMedia->get_page_image($row['id'], "featured_image");

	  if(!empty($featured_image) && count($featured_image)>0) {
	  	$image_attr = array();
	  	$image_alt = "";

	    if($featured_image['at_s3']) $base_img_path = $media_path_s3;
	    else $base_img_path = $media_path_site;

	    if(!empty($featured_image['attributes'])) {
	      $image_attr = json_decode($featured_image['attributes'],true);
	      $image_alt  = isset($image_attr['alt']) ? $image_attr['alt'] : '';
	    }

	    $row['featured_image_url'] = $base_img_path.$featured_image['path'].$featured_image['name'];
	    $row['featured_image_alt'] = $image_alt;
	  } else {
	    $row['featured_image_url'] = get_default_banner_image();
	    $row['featured_image_alt'] = "";
	  }

  	$row['publish_date'] = (!empty($row['publish_date']) && $row['publish_date'] != '0000-00-00 00:00:00') ? date('M d, Y', strtotime($row['publish_date'])) : '';

  	$row['short_description'] = ($row['short_description']) ? trim($row['short_description']) : "";

  	$row['redirect_link'] = $site_url.$blog_path.$row['slug']."/";

  	$xtpl->assign("row", $row);
  	$xtpl->parse('main.row');
  	$cnt = $cnt+1;
   }
}

$xtpl->parse('main');
$xtpl->out('main');
