<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

require_once("include/classes/Page.php");
$obj_page = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

$xtpl = new XTemplate("stubs/Home/PropertyList.html");

$coordinate = $where = array();

// Added by SR - 2 Nov 2022 8:30 am
$where[] = "deleted != 1";

if(isset($page->location) && !empty($page->location)) {
  $where[] = "location = '".$page->location."'";
  $xtpl->assign("LOCATION", $page->location);
  $xtpl->assign("LOCATION_DISP", $location_dom[$page->location]);

  // fetch the secondary content
  $meta = $objPageMeta->get_all_page_meta($page->id);
  if(!empty($meta['property_list_secondary_content'])) $xtpl->assign("PROPERTY_LIST_SECONDARY_CONTENT", $meta['property_list_secondary_content']);
}

$where_str = build_where_clause($where);

$property_list = $obj_page->get_all_properties(100, $where_str, array('property_image'));

$xtpl->assign('CONTENT',$page->content);

if(!empty($property_list)) {

  $xtpl->assign("BREADCRUMB", create_breadcrumb());

  $row_count = 0;

  foreach ($property_list as $key => $property) {
    $page_meta = $objPageMeta->get_all_page_meta($property['id']);
    $xtpl->assign("NAME", isset($property['name']) ? $property['name'] : '');
    $calendly_url = $page_meta['calendly_url'] ?? $calendly_loaction_link[$page->location]['calendly_url'];
    $xtpl->assign("CALENDLY_METTING_URL", $calendly_url);
    $xtpl->assign("CALENDLY_PROPERTY_NAME", rawurlencode(trim($property['name'])) ?? '');
    $xtpl->assign("SHORT_DESCRIPTION", isset($property['short_description']) ? $property['short_description'] : '');
    
    $address = array();
    
    if(!empty($page_meta['property_address'])) $address[] = $page_meta['property_address'];
    if(!empty($page_meta['property_city'])) $address[]    = $location_dom[$page_meta['property_city']];
    if(!empty($page_meta['property_state'])) $address[]   = $page_meta['property_state']. ' '. $page_meta['property_zip'];
    //if(!empty($page_meta['property_zip'])) $address[]     = $page_meta['property_zip'];
    
    if(!empty($page_meta['property_lat']) && !empty($page_meta['property_long'])) {
      $coordinate[] = array($page_meta['property_lat'], $page_meta['property_long']);
      $center_coordinate = calcualte_center_lat_lng($coordinate);
      
      $xtpl->assign('CENTER_LATITUDE', $center_coordinate['center_latitude']);
      $xtpl->assign('CENTER_LONGITUDE', $center_coordinate['center_longitude']);
    }
    
    $xtpl->assign("ADDRESS", implode(', ', $address));
    $xtpl->assign("CALENDLY_PROPERTY_ADDRESS", rawurlencode(implode(', ', $address)));

    if($row_count%2 == 0) {
      $xtpl->assign("SECTION_CLASS", 'grey-box');
    } else {
      $xtpl->assign("SECTION_CLASS", '');
    }
    $id_number = $row_count+1;
    $xtpl->assign("PROPERTY_ID", 'property_block'.$id_number);

    $row_count++;

    if(!empty($property['property_images'])) {
      $image_row_count = 0;
      foreach ($property['property_images'] as $key => $value) {

        if($image_row_count > 4) break;

        if($value['at_s3'] == 1) {
          $xtpl->assign("IMAGE_PATH", $media_path_s3.$value['path'].$value['name']);
        } else {
          $xtpl->assign("IMAGE_PATH", $media_path_site.$value['path'].$value['name']);
        }

        $image_row_count++;

        $xtpl->parse('main.property_list_section.property_slider_section');
      }
    } else {
      $xtpl->parse('main.property_list_section.no_property_slider_section');
    }

    $xtpl->assign("PROPERTY_URI", $property['uri']);

    if(isset($page_meta['matterport_url']) && !empty($page_meta['matterport_url'])) {
      $xtpl->parse('main.property_list_section.virtual_tour_section');
    }
    
    $has_comingsoon = stripos($property['short_description'], 'COMING SOON');
    if ($has_comingsoon === false) {
        $xtpl->parse('main.property_list_section.show_buttton');
        
    
    }
    

    $xtpl->parse('main.property_list_section');
  }

}

$xtpl->parse('main');
$xtpl->out('main');

