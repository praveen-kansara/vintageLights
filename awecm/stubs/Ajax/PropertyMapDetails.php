<?php
if(!defined('__URBAN_OFFICE__')) exit;

$location = (isset($_REQUEST['location']) && !empty($_REQUEST['location'])) ? $_REQUEST['location'] : '';

require_once("include/classes/Page.php");
$obj_page = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

$where = array();

if(isset($location) && !empty($location)) {
  $where[] = "location = '".$location."'";
}

$where_str = build_where_clause($where);

$property_list = $obj_page->get_all_properties(100, $where_str, array('property_image'));

$map_detail = array();

if(!empty($property_list)) {

  $row_count = 1;

  $map_detail["type"]     = "FeatureCollection";
  $map_detail["features"] = array();

  foreach ($property_list as $key => $property) {
    $page_meta = $objPageMeta->get_all_page_meta($property['id']);
    
    if(!empty($page_meta['property_lat']) && !empty($page_meta['property_long'])) {

      $image_path = '';

      if(!empty($property['property_images'])) {

        $value = $property['property_images'][0];

        if($value['at_s3'] == 1) {
          $image_path = $media_path_s3.$value['path'].$value['name'];
        } else {
          $image_path = $media_path_site.$value['path'].$value['name'];
        }
      } 
      
      if(!empty($page_meta['property_brochure'])) $property_brochure_arr = json_decode($page_meta['property_brochure'],1);
      else $property_brochure_arr = array();
    
      if(!empty($property_brochure_arr)) {
        if($property_brochure_arr['at_s3'] == 1) {
          $flyer_path == $media_path_s3.$property_brochure_arr['file'];
        } else {
          $flyer_path = $media_path_site.$property_brochure_arr['file'];
        }
      } else $flyer_path = 'javascript:void(0)';


      $is_coming_soon = 0;
      if ( isset($page_meta['property_is_coming_soon'] ) && ($page_meta['property_is_coming_soon'] == '1')){
        $is_coming_soon  =     1;
      } 

      $property_array = array(
        'type'    => 'Feature',
        'geometry'=> array(
          'type'        => 'Point',
          'coordinates' => array($page_meta['property_long'], $page_meta['property_lat'])
         ),
         
        'properties' => array(
          'name'        => $property['name'],
          'address'     => $page_meta['property_address'],
          'city'        => $location_dom[$page_meta['property_city']],
          'state'       => $page_meta['property_state'],
          "image_path"  => $image_path,
          "description" => $property['short_description'],
          "uri"         => $property['uri'],
          "id"          => "property_block".$row_count,
          "is_coming_soon" => $is_coming_soon,
          "flyer_path"  => $flyer_path
        )
      );

      $row_count++;

      $map_detail['features'][] = $property_array;
    }

  }
}

echo json_encode($map_detail);