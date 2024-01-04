<?php
if(!defined('__URBAN_OFFICE__')) exit;

$xtpl= new XTemplate("modules/OurTeam/EditView.html");

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/PageMedia.php");
$page_media_obj = new PageMedia();

require_once("include/classes/Meta.php");
$meta = new Meta();

$mode = "Add";

$xtpl->assign('HIDE_CLASS', "hidden");

if(isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) ) {
  $xtpl->assign('SUCCESS_MESSAGE', $_SESSION["msg"]);
  $xtpl->parse('main.msg');
  unset($_SESSION["msg"]); 
}

$post_type = '';

if(!empty($_REQUEST['id'])) {

  $xtpl->assign('HIDE_CLASS', "");

  $focus->retrieve($_REQUEST['id']);
  $page_media_row = $page_media_obj->get_page_image($focus->id, 'banner_image');
  if(!empty($page_media_row) && count($page_media_row) > 0) {
    $xtpl->assign('BANNER_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
    $xtpl->assign('MEDIA_ID', $page_media_row['media_id']);
  }

  $link = $site_url.$our_team_path."<span data-slug='".$focus->slug."'>".$focus->slug."/</span>";
  $redirect_link = $site_url.$our_team_path.$focus->slug."/";


  $xtpl->assign('ID',                     $focus->id);
  $xtpl->assign('OUR_TEAM_TITLE',             $focus->title);
  $xtpl->assign('SEQUENCE',               $focus->sequence);
  $xtpl->assign('SLUG_CLASS',             "");
  $xtpl->assign('SLUG_EXISTS',            'slug-exists');
  $xtpl->assign('PERMALINK',              $link);
  $xtpl->assign('PAGE_SLUG',              $focus->slug);
  $xtpl->assign('OUR_TEAM_CONTENT',           $focus->content);
  $xtpl->assign('OUR_TEAM_SHORT_DESCRIPTION', $focus->short_description);
  $xtpl->assign('SEARCH_STATUS', select_list($content_status_dom, $focus->status, 1));

  $post_type = $focus->post_type;

  $page_media_row = $page_media_obj->get_page_image($focus->id, 'featured_image');
  $xtpl->assign('MEDIA_ID_FEATURED_CLASS', "hidden");
  if(!empty($page_media_row) && count($page_media_row) > 0) {
    $xtpl->assign('SOCIAL_SHARE_IMG', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
    $xtpl->assign('MEDIA_ID_FEATURED', $page_media_row['media_id']);
    $xtpl->assign('MEDIA_ID_FEATURED_CLASS', "");
  }

  $page_meta_array =  $meta->get_all_page_meta($_REQUEST['id']);
  $xtpl->assign('META_TITLE',       isset($page_meta_array['meta_title'])       ? $page_meta_array['meta_title'] : "");
  $xtpl->assign('META_KEYWORDS',    isset($page_meta_array['meta_keywords'])    ? $page_meta_array['meta_keywords']    : "");
  $xtpl->assign('META_DESCRIPTION', isset($page_meta_array['meta_description']) ? $page_meta_array['meta_description'] : "");
  $xtpl->assign('DESIGNATION',      isset($page_meta_array['designation'])      ? $page_meta_array['designation'] : "");
  $xtpl->assign('EMAIL',            isset($page_meta_array['email'])            ? $page_meta_array['email'] : "");
  $xtpl->assign('PHONE',            isset($page_meta_array['phone'])            ? $page_meta_array['phone'] : "");
  
  if(isset($page_meta_array['meta_robots_index']) && $page_meta_array['meta_robots_index'] == 1) {
    $xtpl->assign('META_ROBOTS_INDEX', 'checked');
  }

  if(isset($page_meta_array['meta_robots_follow']) && $page_meta_array['meta_robots_follow'] == 1) {
    $xtpl->assign('META_ROBOTS_FOLLOW', 'checked');
  }

  if($focus->status == 'published') {
    $xtpl->assign('HIDE_VIEW_BTN', '');
    $xtpl->assign('PAGE_LINK', $redirect_link);
    if(!empty($focus->publish_date)) {
      $xtpl->assign('PUBLISH_DATE',date('d-m-Y',strtotime($focus->publish_date)) );
    }
  } else {
    $xtpl->assign('HIDE_VIEW_BTN', 'hidden');
  }

  $mode = "Edit";

  if (isset($page_meta_array['vcard']) && !empty($page_meta_array['vcard'])) {
    $vcard = json_decode($page_meta_array['vcard'], 1);
    $vcard_at_s3 = $vcard['at_s3'] == 1 ? true : false;
    
    if (!$vcard_at_s3) {
      $vcard_path =  $site_url.$uploaded_image_path.$vcard['file'];
    } else {
        //s3 retrieve functionality
      $vcard_path = '';
   }
      
    $xtpl->assign('VCARD_PATH', $vcard_path);
    $xtpl->assign('VCARD_PATH_ONLY', $vcard['file']);

  }

} else {
  $xtpl->assign('SEARCH_STATUS', select_list($content_status_dom, '', 1));
  $xtpl->assign('SLUG_CLASS', "hidden");
  $xtpl->assign('MEDIA_ID_FEATURED_CLASS', "hidden");
}

$xtpl->assign('MODE', $mode);
$xtpl->parse('main');
$xtpl->out('main');
