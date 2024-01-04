<?php

#Purpose : EditView File for the property module

if( !defined('__URBAN_OFFICE__') ) exit;

$xtpl= new XTemplate("modules/Property/EditView.html");

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/PageMedia.php");
$page_media_obj = new PageMedia();

require_once("include/classes/Meta.php");
$page_meta_obj = new Meta();

$mode = "Add";
$xtpl->assign('SEARCH_STATUS', select_list($content_status_dom, '', 1));

if(isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) ) {
  $xtpl->assign('SUCCESS_MESSAGE', $_SESSION["msg"]);
  $xtpl->parse('main.msg');
  unset($_SESSION["msg"]); 
}

$xtpl->assign('HIDE_CLASS', "hidden");

if(!empty($_REQUEST['id'])) {

  $res = $focus->retrieve($_REQUEST['id']);

  if(!empty($res)) {

    $page_media_row = $page_media_obj->get_page_image($focus->id, 'banner_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('BANNER_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('MEDIA_ID', $page_media_row['media_id']);
    }

    $page_media_row = $page_media_obj->get_page_image($focus->id, 'second_banner_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('SECOND_BANNER_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('SECOND_BANNER_MEDIA_ID', $page_media_row['media_id']);
    }

    $page_media_row = $page_media_obj->get_page_image($focus->id, 'floor_plan_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('FLOOR_PLAN_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('FLOOR_PLAN_MEDIA_ID', $page_media_row['media_id']);
    }
    
    $page_media_row = $page_media_obj->get_page_image($focus->id, 'floor_plan_zoom_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('FLOOR_PLAN_ZOOM_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('FLOOR_PLAN_ZOOM_MEDIA_ID', $page_media_row['media_id']);
    }
    
    
    /* Start  Floor Plan 2*/
    
    $page_media_row = $page_media_obj->get_page_image($focus->id, 'floor_plan2_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('FLOOR_PLAN2_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('FLOOR_PLAN2_MEDIA_ID', $page_media_row['media_id']);
    }
    
    $page_media_row = $page_media_obj->get_page_image($focus->id, 'floor_plan2_zoom_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('FLOOR_PLAN2_ZOOM_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('FLOOR_PLAN2_ZOOM_MEDIA_ID', $page_media_row['media_id']);
    }
    
    /* END */
    
    
    /* Start  Floor Plan 3*/
    
    $page_media_row = $page_media_obj->get_page_image($focus->id, 'floor_plan3_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('FLOOR_PLAN3_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('FLOOR_PLAN3_MEDIA_ID', $page_media_row['media_id']);
    }
    
    $page_media_row = $page_media_obj->get_page_image($focus->id, 'floor_plan3_zoom_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('FLOOR_PLAN3_ZOOM_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('FLOOR_PLAN3_ZOOM_MEDIA_ID', $page_media_row['media_id']);
    }
    
    /* END */
    
    
    $page_media_row = $page_media_obj->get_page_image($focus->id, 'video_placeholder_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('VIDEO_PLACEHOLDER_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('VIDEO_PLACEHOLDER_MEDIA_ID', $page_media_row['media_id']);
    }

    $page_media_row = $page_media_obj->get_page_image($focus->id, 'featured_image');
    if(!empty($page_media_row) && count($page_media_row) > 0) {
      $xtpl->assign('FEATURED_IMAGE', get_image_absolute_path($page_media_row['path'], $page_media_row['name']));
      $xtpl->assign('MEDIA_ID_FEATURED', $page_media_row['media_id']);
    }

    $link = $site_url.$property_path."<span data-slug='".$focus->slug."'>".$focus->slug."/</span>";
    $redirect_link = $site_url.$property_path.$focus->slug."/";

    $page_media_results = $page_media_obj->get_page_images($focus->id, 'property_image','', 'page_media.sequence*1 asc');
    if(count($page_media_results) > 0) {
      foreach ($page_media_results as $page_media_images) {
        $xtpl->assign('PROPERTY_IMAGE', get_image_absolute_path($page_media_images['path'], $page_media_images['name']));
        $xtpl->assign('PROPERTY_IMAGE_ID', $page_media_images['media_id']);
        $xtpl->parse('main.property_image');
      }
    }

    $checked = $focus->tag == "featured" ? "checked" : "";

    $xtpl->assign('ID',                          $_REQUEST['id']);
    $xtpl->assign('HIDE_CLASS',                  "");
    $xtpl->assign('PROPERTY_TITLE',               $focus->title);
    $xtpl->assign('SEQUENCE',                    $focus->sequence);
    $xtpl->assign('SLUG_CLASS',                  "");
    $xtpl->assign('SLUG_EXISTS',                 'slug-exists');
    $xtpl->assign('PERMALINK',                   $link);
    $xtpl->assign('PAGE_SLUG',                   $focus->slug);
    $xtpl->assign('PROPERTY_CONTENT',             $focus->content);
    $xtpl->assign('FEATURED',                    $checked);
    $xtpl->assign('PROPERTY_SHORT_DESCRIPTION',  $focus->short_description);

    $page_meta_array =  $page_meta_obj->get_all_page_meta($_REQUEST['id']);

    if(isset($page_meta_array['property_city'])) $property_city = $page_meta_array['property_city'];
    else $property_city = "";

    $xtpl->assign('PROPERTY_CITY', select_list($location_dom, $property_city, 1));

    $xtpl->assign('PROPERTY_STATE', isset($page_meta_array['property_state']) ? $page_meta_array['property_state'] : "");
    $xtpl->assign('MATTERPORT_URL', isset($page_meta_array['matterport_url']) ? $page_meta_array['matterport_url'] : "");
    
    $xtpl->assign('MATTERPORT_URL2', isset($page_meta_array['matterport_url2']) ? $page_meta_array['matterport_url2'] : "");
    
    $xtpl->assign('PROPERTY_ADDRESS',isset($page_meta_array['property_address']) ? $page_meta_array['property_address'] : "");
    $xtpl->assign('PROPERTY_LAT', isset($page_meta_array['property_lat']) ? $page_meta_array['property_lat'] : "");
    $xtpl->assign('PROPERTY_LONG', isset($page_meta_array['property_long']) ? $page_meta_array['property_long'] : "");
    $xtpl->assign('PROPERTY_ZIP', isset($page_meta_array['property_zip']) ? $page_meta_array['property_zip'] : "");
    $xtpl->assign('PROPERTY_VIDEO', isset($page_meta_array['property_video']) ? $page_meta_array['property_video'] : "");
    $xtpl->assign('WALKABLE_AMINITY', isset($page_meta_array['walkable_aminity']) ? $page_meta_array['walkable_aminity'] : "");
    $xtpl->assign('ONSITE_AMINITY', isset($page_meta_array['onsite_aminity']) ? $page_meta_array['onsite_aminity'] : "");
    $xtpl->assign('PROPERTY_LOCATION_CODE', isset($page_meta_array['property_location_code']) ? $page_meta_array['property_location_code'] : "");
    $xtpl->assign('CALENDLY_URL', isset($page_meta_array['calendly_url']) ? $page_meta_array['calendly_url'] : "");

    $xtpl->assign('FLOOR_PLAN_TITLE', isset($page_meta_array['floor_plan_title']) ? $page_meta_array['floor_plan_title'] : "");
    
    
    $xtpl->assign('FLOOR_PLAN2_TITLE', isset($page_meta_array['floor_plan2_title']) ? $page_meta_array['floor_plan2_title'] : "");
    
    
    $xtpl->assign('FLOOR_PLAN3_TITLE', isset($page_meta_array['floor_plan3_title']) ? $page_meta_array['floor_plan3_title'] : "");
    
    $xtpl->assign('FLOOR_PLAN_SUB_TITLE', isset($page_meta_array['floor_plan_sub_title']) ? $page_meta_array['floor_plan_sub_title'] : "");

    if(!empty($page_meta_array['property_amenities'])) $amenities_array = json_decode($page_meta_array['property_amenities']);
    else $amenities_array = array();

    foreach ($amenities_dom as $key => $value) {
      $xtpl->assign('AMENITIES_TITLE', ucwords($value['title']));
      $xtpl->assign('AMENITIES_VALUE', $key);

      if(in_array($key, $amenities_array)) {
        $xtpl->assign('AMENITIES_STATUS', "checked");
      } else {
        $xtpl->assign('AMENITIES_STATUS', "");
      }

      $xtpl->parse('main.amenities_block');
    }

    if (isset($page_meta_array['property_brochure']) && !empty($page_meta_array['property_brochure'])) {
      $property_brochure = json_decode($page_meta_array['property_brochure'], 1);
      $brochure_at_s3 = $property_brochure['at_s3'] == 1 ? true : false;

      if (!$brochure_at_s3) {
        $pdf_path =  $site_url.$uploaded_image_path.$property_brochure['file'];
      } else {
        //s3 retrieve functionality
        $pdf_path = '';
      }

      $xtpl->assign('BROCHURE_PATH', $pdf_path);
      $xtpl->assign('BROCHURE_PATH_ONLY', $property_brochure['file']);
      $xtpl->parse('main.view_brochure');
    } else {
      $xtpl->parse('main.input_brochure');
    }

    $xtpl->assign('META_TITLE',       isset($page_meta_array['meta_title'])       ? $page_meta_array['meta_title']       : "");
    $xtpl->assign('META_KEYWORDS',    isset($page_meta_array['meta_keywords'])    ? $page_meta_array['meta_keywords']    : "");
    $xtpl->assign('META_DESCRIPTION', isset($page_meta_array['meta_description']) ? $page_meta_array['meta_description'] : "");

    $xtpl->assign('SEARCH_STATUS', select_list($content_status_dom, $focus->status, 1));

    if(isset($page_meta_array['meta_robots_index']) && $page_meta_array['meta_robots_index'] == 1) {
      $xtpl->assign('META_ROBOTS_INDEX','checked');
    }

    if(isset($page_meta_array['meta_robots_follow']) && $page_meta_array['meta_robots_follow'] == 1) {
      $xtpl->assign('META_ROBOTS_FOLLOW','checked');
    }

    if($focus->status == 'published') {
      $xtpl->assign('HIDE_VIEW_BTN', '');
      $xtpl->assign('PAGE_LINK', $redirect_link);

    } else {
      $xtpl->assign('HIDE_VIEW_BTN', 'hidden');
    }

    $mode = "Edit";

  } else redirect("./?module=Property&action=index");


} else {

  foreach ($amenities_dom as $key => $value) {
    $xtpl->assign('AMENITIES_TITLE', ucwords($value['title']));
    $xtpl->assign('AMENITIES_VALUE', $key);
    $xtpl->parse('main.amenities_block');
  }

  $xtpl->assign('SEARCH_STATUS', select_list($content_status_dom, "", 1));
  $xtpl->assign('PROPERTY_CITY', select_list($location_dom,"", 1));
  $xtpl->assign('SLUG_CLASS', "hidden");
}

$xtpl->assign('MODE', $mode);
$xtpl->parse('main');
$xtpl->out('main');
