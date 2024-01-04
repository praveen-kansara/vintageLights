<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

$max_entries_per_page = 20;

require_once("include/classes/Page.php");
$obj_page = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

require_once("include/classes/Media.php");
$objPageMedia = new PageMedia();

$xtpl = new XTemplate("stubs/Home/OurTeam.html");
$xtpl->assign("BREADCRUMB", create_breadcrumb());

$where = array();
$where[] = "post_type = 'our_team'";
$where[] = "deleted = false";
$where[] = "status = 'published'";
$where[] = "sequence != ''";
$max_entries_per_page  = 50;

$where_cls = build_where_clause($where);
$query = $obj_page->generate_list_query($where_cls, 'sequence*1 ASC');

$response = $focus->get_list_by_query($query);
$cnt = 1;
$col = 1;
if($response['list']) {
  foreach($response['list'] as $seed) {
    $row['cls'] = '';

    $row = $seed->get_list_view_data();
    
    $page_meta = $objPageMeta->get_all_page_meta($row['id']);
    
    $designation_txt = isset($page_meta['designation']) ? '<h5>'.$page_meta['designation'].'</h5>' : "";
    $email_txt       = isset($page_meta['email'])       ? '<p class="mb-0"><a href="mailto:'.$page_meta['email'].'" targer="_blank">'.$page_meta['email'].'</a></p>'       : "";
    $phone_txt       = isset($page_meta['phone'])       ? '<p class="mb-0">'.$page_meta['phone'].'</p>'       : "";
    
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

   $banner_image = $objPageMedia->get_page_image($page->id, "banner_image");

    if(!empty($banner_image) && count($banner_image)>0) {
      if($banner_image['at_s3']) $base_img_path = $media_path_s3;
      else $base_img_path = $media_path_site;
      $xtpl->assign('BANNER_URL', $base_img_path.$banner_image['path'].$banner_image['name']);
      } else {
        $xtpl->assign('BANNER_URL', get_default_banner_image());
      }


  	$row['short_description'] = ($row['short_description']) ? trim($row['short_description']) : "";

  	$row['redirect_link'] = $site_url.$our_team_path.$row['slug']."/";
    
  	    
  	    $team_member_gallery = $mb_class = '';
  	    
  	    
  	    
  
	       // if($col == 1 ){
	       //     $team_member_gallery .= '<div class="row mb-100 justify-content-center">';
	       // }

    	    $team_member_gallery .= '<div class="col-12 col-md-4">
                                    <div class="member-box">
                                        <img src="'.$row["featured_image_url"].'" alt="'.$row["featured_image_alt"].'" title="'.$row["featured_image_alt"].'" />
                                        <div class="img-card">
                                            <h4>'.$row["title_txt"].'</h4>
                                            '.$designation_txt.$email_txt.$phone_txt.'
                                        </div>
                                    </div>
                                </div>';	   
    
  	    $xtpl->assign("TEAM_MEMBER_GALLERY", $team_member_gallery);
  	    $xtpl->parse('main.team_member_section');
  	    
  	    
  	    
  	    $col ++;
  	$cnt = $cnt+1;
   }
}

$page->content = $focus->process_short_codes($page->content);

$page->content = media_pre_processor($page->content);
$xtpl->assign("CONTENT", $page->content);

$xtpl->parse('main');
$xtpl->out('main');
