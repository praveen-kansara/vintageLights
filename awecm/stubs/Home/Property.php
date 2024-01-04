<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

require_once("include/classes/Page.php");
$obj_page = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

require_once("include/classes/Media.php");
$objPageMedia = new PageMedia();

$xtpl = new XTemplate("stubs/Home/Property.html");

$xtpl->assign("HTTP_USER_AGENT", $_SERVER['HTTP_USER_AGENT']);
$xtpl->assign("REMOTE_ADDR", $_SERVER['REMOTE_ADDR']);

if($page) {

  echo $page->location;
  
  $xtpl->assign('LOCAL_CDN_PATH', $local_cdn_image);

  $xtpl->assign("BREADCRUMB", create_breadcrumb_for_property($page->location));

  $page_meta = $objPageMeta->get_all_page_meta($page->id);

  $banner_image = $objPageMedia->get_page_image($page->id, "banner_image");

  if(!empty($banner_image) && count($banner_image)>0) {
    if($banner_image['at_s3']) $base_img_path = $media_path_s3;
    else $base_img_path = $media_path_site;
    $xtpl->assign('BANNER_URL', $base_img_path.$banner_image['path'].$banner_image['name']);
  } else {
    $xtpl->assign('BANNER_URL', get_default_banner_image());
  }

  $page->content = $obj_page->process_short_codes($page->content);

  $page->content = media_pre_processor($page->content);
  $xtpl->assign("CONTENT", $page->content);
  $xtpl->assign("PAGE_TITLE", $page->title);
  $xtpl->assign("PAGE_SUB_TITLE", $page->short_description);
  $xtpl->assign("PROPERTY_ID",$page->id);
  $xtpl->assign("CALENDLY_METTING_URL", $page_meta['calendly_url'] ?? $calendly_loaction_link[$page->location]['calendly_url']);
  $xtpl->assign("CALENDLY_PROPERTY_NAME", rawurlencode(trim($page->title)));

  $address = array();

  if(!empty($page_meta['property_brochure'])) $property_brochure_arr = json_decode($page_meta['property_brochure'],1);
  else $property_brochure_arr = array();

  if(!empty($property_brochure_arr)) {
    if($property_brochure_arr['at_s3'] == 1) {
      $flyer_path == $media_path_s3.$property_brochure_arr['file'];
    } else {
      $flyer_path = $media_path_site.$property_brochure_arr['file'];
    }
    $xtpl->assign('FLYER_LINK', $flyer_path);
    $xtpl->parse('main.flyer_link_section');
    $xtpl->parse('main.flyer_link_section_top');
  }


  if(!empty($page_meta['property_address'])) $address[] = $page_meta['property_address'];
  if(!empty($page_meta['property_city'])) $address[]    = $location_dom[$page_meta['property_city']];
  if(!empty($page_meta['property_state'])) $address[]   = $page_meta['property_state']. ' '.$page_meta['property_zip'] ;
//   if(!empty($page_meta['property_zip'])) $address[]     = $page_meta['property_zip'];

  $xtpl->assign("ADDRESS", implode(', ', $address));
  // $xtpl->assign("CALENDLY_PROPERTY_ADDRESS",str_replace(" ","%20",implode(', ', $address)));
  $xtpl->assign("CALENDLY_PROPERTY_ADDRESS", rawurlencode(implode(', ', $address)));

  if(isset($page_meta['property_video']) && !empty($page_meta['property_video'])) {
    $xtpl->assign("VIDEO_CODE", $page_meta['property_video']);
    $xtpl->parse('main.video_section');
  } else {
    $video_placeholder_image = $objPageMedia->get_page_image($page->id, "video_placeholder_image");
    if(!empty($video_placeholder_image)) {
      if($video_placeholder_image['at_s3']) $base_img_path = $media_path_s3;
      else $base_img_path = $media_path_site;
      $xtpl->assign('VIDEO_PLACEHOLDER_IMAGE_URL', $base_img_path.$video_placeholder_image['path'].$video_placeholder_image['name']);
      $xtpl->parse('main.no_video_section');
    }
  }

  $property_images = $objPageMedia->get_page_images($page->id, 'property_image', 50, 'sequence*1 desc');
  if(!empty($property_images)) {
    $image_count = $gallery_block_count = 1;
    $loop_count = 0;

    $parse_image_count = $total_image_count = count($property_images);

    $gallery_str = "";

    foreach ($property_images as $key => $value) {

      $loop_count++;

      if($value['at_s3'] == 1) {
        $image_path = $media_path_s3.$value['path'].$value['name'];
      } else {
        $image_path = $media_path_site.$value['path'].$value['name'];
      }

      $image_attr = json_decode($value['attributes'],true);

      $image_alt =  $image_attr['alt'];

      if($gallery_block_count%2 != 0) {

        if($parse_image_count < 3 || $total_image_count < 3) {
          if($image_count == 1) {
            $gallery_str.= '<div class="row mb-15">
                               <div class="col-12 col-md-4 pr-10 img-box">
                                  <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                                </div>';
          } else {
            $gallery_str.= '<div class="col-12 col-md-4 pl-10 img-box">
                              <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                            </div>';
          }

          if($image_count%2 == 0 || $total_image_count == $loop_count) {
            $gallery_str.= '</div>';
          } else {
            $image_count++;
          }

        } else {
          if($image_count == 1) {
            $gallery_str.='<div class="row mb-15">
                              <div class="col-12 col-md-8 pr-10 img-box">
                                <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                              </div>';
          } else {
            if($image_count == 2) {
              $gallery_str.= '<div class="col-12 col-md-4 pl-10">
                                <div class="row">
                                  <div class="col-12 mb-15 pl-10 img-box">
                                    <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                                  </div>
                                </div>';
            } else {
              $gallery_str.= '<div class="row">
                                <div class="col-12 pl-10 img-box">
                                  <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                                </div>
                              </div>';
            }

            if($image_count%3 == 0 || $total_image_count == $loop_count) {
              $gallery_str.= '</div>';
            }
          }

          if($image_count%3 == 0 || $total_image_count == $loop_count) {
            $parse_image_count = $parse_image_count - 3;
            $gallery_block_count++;
            $image_count = 1;
            $gallery_str.= '</div>';
          } else {
            $image_count++ ;
          }
        }
      } else {
        if($parse_image_count < 3 || $total_image_count < 3) {
          if($image_count == 1) {
            $gallery_str.= '<div class="row mb-15">
                               <div class="col-12 col-md-4 pr-10 img-box">
                                  <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                                </div>';
          } else {
            $gallery_str.= '<div class="col-12 col-md-4 pl-10 img-box">
                              <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                            </div>';
          }

          if($image_count%2 == 0 || $total_image_count == $loop_count) {
            $gallery_str.= '</div>';
          } else {
            $image_count++;
          }

        } else {
          if($image_count == 3) {

            $gallery_str.= '<div class="col-12 col-md-8 pl-10 img-box">
                              <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                            </div>';
          } else {
            if($image_count == 1) {
              $gallery_str.= '<div class="row mb-15">
                                 <div class="col-12 col-md-4 pr-10">
                                    <div class="row">
                                      <div class="col-12 mb-15 pr-10 img-box">
                                        <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                                      </div>
                                    </div>';
            } else {
              $gallery_str.= '<div class="row">
                                <div class="col-12 pr-10 img-box">
                                  <img src="'.$image_path.'" width="1200" height="1200" alt="'.$image_alt.'" title="'.$image_alt.'">
                                </div>
                              </div>';
            }

            if($image_count%2 == 0 || $total_image_count == $loop_count) {
              $gallery_str.= '</div>';
            }
          }

          if($image_count%3 == 0 || $total_image_count == $loop_count) {
            $parse_image_count = $parse_image_count - 3;
            $gallery_str.= '</div>';
            $gallery_block_count++;
            $image_count = 1;
          } else {
            $image_count++ ;
          }
        }
      }
    }

    $xtpl->assign('GALLERY_SECTION', $gallery_str);
    $xtpl->parse('main.gallery_section');
  }

  $floor_plan_image = $objPageMedia->get_page_image($page->id, "floor_plan_image");
  $floor_plan2_image = $objPageMedia->get_page_image($page->id, "floor_plan2_image");

  if(!empty($floor_plan_image) && count($floor_plan_image)>0) {
    if($floor_plan_image['at_s3']) $base_img_path = $media_path_s3;
    else $base_img_path = $media_path_site;
    
    $floor_plan_zoom_image = $objPageMedia->get_page_image($page->id, "floor_plan_zoom_image");
    
    if(!empty($floor_plan_zoom_image) && count($floor_plan_zoom_image)>0) {
      if($floor_plan_zoom_image['at_s3']) $base_img_zoom_path = $media_path_s3;
      else $base_img_zoom_path = $media_path_site;
      
      $xtpl->assign('FLOOR_PLAN_ZOOM_IMAGE_URL', $base_img_zoom_path.$floor_plan_zoom_image['path'].$floor_plan_zoom_image['name']);
      
    } else {
      $xtpl->assign('FLOOR_PLAN_ZOOM_IMAGE_URL', $base_img_path.$floor_plan_image['path'].$floor_plan_image['name']);    
    }

    $image_attr = json_decode($floor_plan_image['attributes'],true);

    $xtpl->assign('FLOOR_PLAN_IMAGE_URL', $base_img_path.$floor_plan_image['path'].$floor_plan_image['name']);

    if(!empty($page_meta['floor_plan_title'])) $floor_plan_title = $page_meta['floor_plan_title'];
    else $floor_plan_title = 'Floor Plan';

    if(!empty($page_meta['floor_plan_sub_title'])) $floor_plan_sub_title = $page_meta['floor_plan_sub_title'];
    else $floor_plan_sub_title = '';

    $xtpl->assign("FLOOR_PLAN_TITLE", $floor_plan_title);
    $xtpl->assign("FLOOR_PLAN_IMAGE_WIDTH", $image_attr['width']);
    $xtpl->assign("FLOOR_PLAN_IMAGE_HEIGHT", $image_attr['height']);
    $xtpl->assign("FLOOR_PLAN_SUB_TITLE", $floor_plan_sub_title);
    if(!empty($floor_plan2_image) && count($floor_plan2_image)>0 ) $xtpl->assign("COLUMN_CLASS", "col-md-6");
    else $xtpl->assign("COLUMN_CLASS", "col-md-12");
    
    $xtpl->parse('main.floor_plan_section');
  }
  
  
  $floor_plan2_image = $objPageMedia->get_page_image($page->id, "floor_plan2_image");
  
  if(!empty($floor_plan2_image) && count($floor_plan2_image)>0) {
    if($floor_plan2_image['at_s3']) $base_img_path = $media_path_s3;
    else $base_img_path = $media_path_site;
    
    $floor_plan2_zoom_image = $objPageMedia->get_page_image($page->id, "floor_plan2_zoom_image");
    
    if(!empty($floor_plan2_zoom_image) && count($floor_plan2_zoom_image)>0) {
      if($floor_plan2_zoom_image['at_s3']) $base_img_zoom_path = $media_path_s3;
      else $base_img_zoom_path = $media_path_site;
      
      $xtpl->assign('FLOOR_PLAN2_ZOOM_IMAGE_URL', $base_img_zoom_path.$floor_plan2_zoom_image['path'].$floor_plan2_zoom_image['name']);
      
    } else {
      $xtpl->assign('FLOOR_PLAN2_ZOOM_IMAGE_URL', $base_img_path.$floor_plan2_image['path'].$floor_plan2_image['name']);    
    }

    $image_attr = json_decode($floor_plan2_image['attributes'],true);

    $xtpl->assign('FLOOR_PLAN2_IMAGE_URL', $base_img_path.$floor_plan2_image['path'].$floor_plan2_image['name']);

    if(!empty($page_meta['floor_plan2_title'])) $floor_plan2_title = $page_meta['floor_plan2_title'];
    else $floor_plan2_title = 'Floor Plan';

    if(!empty($page_meta['floor_plan2_sub_title'])) $floor_plan2_sub_title = $page_meta['floor_plan2_sub_title'];
    else $floor_plan2_sub_title = '';

    $xtpl->assign("FLOOR_PLAN2_TITLE", $floor_plan2_title);
    $xtpl->assign("FLOOR_PLAN2_IMAGE_WIDTH", $image_attr['width']);
    $xtpl->assign("FLOOR_PLAN2_IMAGE_HEIGHT", $image_attr['height']);
    //$xtpl->assign("FLOOR_PLAN2_SUB_TITLE", $floor_plan2_sub_title);

    $xtpl->parse('main.floor_plan2_section');
  }
  
  $floor_plan3_image = $objPageMedia->get_page_image($page->id, "floor_plan3_image");
  
  if(!empty($floor_plan3_image) && count($floor_plan3_image)>0) {
    if($floor_plan3_image['at_s3']) $base_img_path = $media_path_s3;
    else $base_img_path = $media_path_site;
    
    $floor_plan3_zoom_image = $objPageMedia->get_page_image($page->id, "floor_plan3_zoom_image");
    
    if(!empty($floor_plan3_zoom_image) && count($floor_plan3_zoom_image)>0) {
      if($floor_plan3_zoom_image['at_s3']) $base_img_zoom_path = $media_path_s3;
      else $base_img_zoom_path = $media_path_site;
      
      $xtpl->assign('FLOOR_PLAN3_ZOOM_IMAGE_URL', $base_img_zoom_path.$floor_plan3_zoom_image['path'].$floor_plan3_zoom_image['name']);
      
    } else {
      $xtpl->assign('FLOOR_PLAN3_ZOOM_IMAGE_URL', $base_img_path.$floor_plan3_image['path'].$floor_plan3_image['name']);    
    }

    $image_attr = json_decode($floor_plan2_image['attributes'],true);

    $xtpl->assign('FLOOR_PLAN3_IMAGE_URL', $base_img_path.$floor_plan3_image['path'].$floor_plan3_image['name']);

    if(!empty($page_meta['floor_plan3_title'])) $floor_plan3_title = $page_meta['floor_plan3_title'];
    else $floor_plan3_title = 'Floor Plan';

    if(!empty($page_meta['floor_plan3_sub_title'])) $floor_plan3_sub_title = $page_meta['floor_plan3_sub_title'];
    else $floor_plan3_sub_title = '';

    $xtpl->assign("FLOOR_PLAN3_TITLE", $floor_plan3_title);
    $xtpl->assign("FLOOR_PLAN3_IMAGE_WIDTH", $image_attr['width']);
    $xtpl->assign("FLOOR_PLAN3_IMAGE_HEIGHT", $image_attr['height']);
    //$xtpl->assign("FLOOR_PLAN3_SUB_TITLE", $floor_plan3_sub_title);

    $xtpl->parse('main.floor_plan3_section');
  }

  if(!empty($page_meta['property_amenities'])) $amenities_array = json_decode($page_meta['property_amenities']);
  else $amenities_array = array();

  if(!empty($amenities_array)) {
    $amenity_count = 1;
    $total_amenity_count = count($amenities_array);
    foreach ($amenities_array as $key => $amenity_key) {

      $xtpl->assign("AMINITY_TITLE",  $amenities_dom[$amenity_key]['title']);
      $xtpl->assign("AMINITY_IMAGE",  $local_cdn_image.$amenities_dom[$amenity_key]['image_name']);
      $xtpl->assign("AMINITY_WIDTH",  $amenities_dom[$amenity_key]['width']);
      $xtpl->assign("AMINITY_HEIGHT", $amenities_dom[$amenity_key]['height']);

      $xtpl->parse('main.amenities_section.amenities_section_row.amenities_section_column');

      if($amenity_count%4 == 0 || $total_amenity_count == $amenity_count) {
        $xtpl->parse('main.amenities_section.amenities_section_row');
      }

      $amenity_count++;
    }
    $xtpl->parse('main.amenities_section');
  }

//   $second_banner_image = $objPageMedia->get_page_image($page->id, "second_banner_image");

//   if(!empty($second_banner_image) && count($second_banner_image)>0) {
//     if($second_banner_image['at_s3']) $base_img_path = $media_path_s3;
//     else $base_img_path = $media_path_site;
//     $xtpl->assign('SECOND_BANNER_IMAGE_URL', $base_img_path.$second_banner_image['path'].$second_banner_image['name']);
//     $xtpl->assign('MATTERPORT_URL', $page_meta['matterport_url']);

//     $xtpl->parse('main.second_banner_section');
//   }
  $display_virtual_tour = false;
  if(@$page_meta['matterport_url']){
    $display_virtual_tour = true;  
    $xtpl->assign('MATTERPORT_URL', $page_meta['matterport_url']);
    $xtpl->assign('CURRENT_URL', $current_full_url);
    
    
  }
  
  
  if(!empty($page_meta['matterport_url2'])){
      $display_virtual_tour = true;    
    $xtpl->assign('MATTERPORT_URL2', $page_meta['matterport_url2']);
    $xtpl->assign('CURRENT_URL', $current_full_url);
   
  }
  if($display_virtual_tour){
    $xtpl->parse('main.virtual_tour_button');  
    $xtpl->parse('main.virtual_tour_section');    
  }
    
 
  if(!empty($page_meta['walkable_aminity'])) $walkable_aminity_array = explode(PHP_EOL, $page_meta['walkable_aminity']);
  else $walkable_aminity_array = array();

  if(!empty($page_meta['onsite_aminity'])) $onsite_aminity_array = explode(PHP_EOL, $page_meta['onsite_aminity']);
  else $onsite_aminity_array = array();

  if(!empty($onsite_aminity_array) || $walkable_aminity_array) {

    if(!empty($walkable_aminity_array)) {
      $walk_amenity_count = 1;
      $walk_amenty_str = $even_walk_mainty_str = $odd_walk_mainty_str = '';
      foreach ($walkable_aminity_array as $key => $walk_amenity_value) {

        if($walk_amenity_count%2 != 0) {
          $odd_walk_mainty_str .= '<li>'.$walk_amenity_value.'</li>';
        } else {
          $even_walk_mainty_str .= '<li>'.$walk_amenity_value.'</li>';
        }

        $walk_amenity_count++;
      }

      $walk_amenty_str .= '<div class="col-12 col-md-5 pl-60"><ul>'.$odd_walk_mainty_str.'</ul></div>';

      $walk_amenty_str .= '<div class="col-12 col-md-5 pl-80"><ul>'.$even_walk_mainty_str.'</ul></div>';

      $xtpl->assign('WALKABLE_AMENITY_CONTENT', $walk_amenty_str);
      $xtpl->parse('main.walkable_amenity_section');
    }

     if(!empty($onsite_aminity_array)) {
      $onsite_amenity_count = 1;
      $onsite_amenty_str = $even_onsite_mainty_str = $odd_onsite_mainty_str = '';
      foreach ($onsite_aminity_array as $key => $onsite_amenity_value) {

        if($onsite_amenity_count%2 != 0) {
          $odd_onsite_mainty_str .= '<li>'.$onsite_amenity_value.'</li>';
        } else {
          $even_onsite_mainty_str .= '<li>'.$onsite_amenity_value.'</li>';
        }

        $onsite_amenity_count++;
      }

      $onsite_amenty_str .= '<div class="col-12 col-md-5 pl-60"><ul>'.$odd_onsite_mainty_str.'</ul></div>';

      $onsite_amenty_str .= '<div class="col-12 col-md-5 pl-80"><ul>'.$even_onsite_mainty_str.'</ul></div>';

      $xtpl->assign('ONSITE_AMENITY_CONTENT', $onsite_amenty_str);
      $xtpl->parse('main.onsite_amenity_section');
    }
  }

  if(!empty($page_meta['property_location_code'])) $property_location_code = $page_meta['property_location_code'];
  else $property_location_code = '';

  if(!empty($property_location_code)) {
    $xtpl->assign('PROPERTY_LOCATION_CODE', $property_location_code);
    $xtpl->parse('main.property_location_section');
  }

  $where = array();
  //$where[] = "location = '".$page->location."'";
  $where[] = "id != '".$page->id."'";

  $where_str = build_where_clause($where);

  $property_list = $obj_page->get_all_properties(15, $where_str);
  if(!empty($property_list)) {

    $xtpl->assign('LOCATION', implode('',array_values($location_dom)));

    foreach ($property_list as $ky => $property_details) {
      $property_name_arr   = array();
      $property_name_arr[] = $property_details['name'];

      if(!empty($property_details['location'])) $property_name_arr[] = $location_dom[$property_details['location']];

      $property_image_path = $property_image_alt = "";

      if(!empty($property_details['icon'])) {
        if(is_file($uploaded_image_path.$property_details['icon']['path'].$property_details['icon']['name'])) {
          $property_image_path = createImageWithSize($property_details['icon'], '555x555', 1);
          $property_image_attr = json_decode($property_details['icon']['attributes'],1);
          $property_image_alt  = $property_image_attr['alt'];
        }
      }

      $xtpl->assign('NAME', implode(', ', $property_name_arr));
      $xtpl->assign('IMAGE_PATH', $property_image_path);
      $xtpl->assign('PROPERTY_IMAGE_ALT', $property_image_alt);
      $xtpl->assign('PROPERTY_URI', $property_details['uri']);
      $xtpl->parse('main.similar_property_section.similar_property_section_property');
    }
    $xtpl->parse('main.similar_property_section');
  }

} else {
}

$xtpl->parse('main');
$xtpl->out('main');
