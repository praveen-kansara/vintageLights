<?php
#Purpose: To save the page information

if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/Page.php");
$focus = New Page();

require_once("include/classes/PageMedia.php");
$page_media_obj = New PageMedia();

require_once("include/classes/Meta.php");
$meta = new Meta();

$is_ajax_request = false;
$module_type = 'our_team';

if(isset($_REQUEST['type']) && $_REQUEST['type']=="ajax") {
  # its a ajax request on save.php
  $is_ajax_request = true;
}

// Check this
$is_new_entry = true;

if($_REQUEST['status'] == 'published'  &&  empty($_REQUEST['id'])) { $_SESSION["msg"] = _PUBLISHED_MSG; }
elseif ($_REQUEST['status'] == 'published' && !empty($_REQUEST['id'])) { $_SESSION["msg"] = _UPDATE;     }
elseif ($_REQUEST['status'] == 'draft') { $_SESSION["msg"] = _DRAFT_MSG ; }

if(isset($_REQUEST['id']) && $_REQUEST['id']!="" ) {
  $focus->retrieve($_REQUEST['id']);
  $is_new_entry = false;
  $tag_string = $focus->tag;

  #remove old cache here
  $page_url = $focus->uri;
  if($page_url) remove_cache($page_url);

  // study how to manage tag in array structure so that we could save multiple tags using same form.
  if(!isset($_REQUEST['tag'])) {
    $_REQUEST['tag'] = 'our_team_detail_template';
  }
}

if(isset($_REQUEST['slug'])){
  $focus->uri = $site_url.$our_team_path.$_REQUEST['slug']."/";
}


foreach($focus->column_fields as $field) {
  if(isset($_REQUEST[$field])) {
    $value = $_REQUEST[$field];
    $focus->$field = $value;
  }
}

# Save publish date
if(!empty($_REQUEST['publish_date']) && ($_REQUEST['status'] == 'published') ) {
  $publish_date = date('Y-m-d H:i:s', strtotime($_REQUEST['publish_date']));
  $focus->publish_date = $publish_date;
} else if($_REQUEST['status'] == 'draft') 
  $focus->publish_date = NULL;

# Fire save event
$focus->save();

#save meta keywords and meta description
$page_id = $focus->id;

$meta_robots_index  = isset($_REQUEST['meta_robots_index'])  ? $_REQUEST['meta_robots_index']  : 0;
$meta_robots_follow = isset($_REQUEST['meta_robots_follow']) ? $_REQUEST['meta_robots_follow'] : 0;
$designation        = isset($_REQUEST['designation'])        ? $_REQUEST['designation']        : '';
$email              = isset($_REQUEST['email'])              ? $_REQUEST['email']              : '';
$phone              = isset($_REQUEST['phone'])              ? $_REQUEST['phone']              : '';

#Created for saving multiple values
$meta_param_dom = array(
  "meta_title"          => $_REQUEST['meta_title'],
  "meta_description"    => $_REQUEST['meta_description'],
  "meta_robots_index"   => $meta_robots_index,
  "meta_robots_follow"  => $meta_robots_follow,
);

if(isset($_REQUEST['meta_keywords'])) {
  $meta_param_dom['meta_keywords'] =  $_REQUEST['meta_keywords'];
}

if(!empty($_REQUEST['designation'])) {
  $meta_param_dom['designation'] =  $designation;
}

if(!empty($_REQUEST['email'])) {
  $meta_param_dom['email'] =  $email;
}

if(!empty($_REQUEST['phone'])) {
  $meta_param_dom['phone'] =  $phone;
}

#-----------------------------------------------
# START:  Save vcard brochure
# Note: We've managed an hidden input 'vcard_path'
#-----------------------------------------------
$meta_param_dom['property_brochure'] = "";

if ($_FILES['vcard']['name']) {
  
  $file_name = prettify_image_name($_FILES['vcard']['name']);

  # Getting extension of uploaded file
  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
 
  if(in_array(strtolower($file_extension), array('vcf'))) {
    
    $base_file_name = basename($file_name, ".".pathinfo($file_name, PATHINFO_EXTENSION));

    #Creating directories in file path directory
    $path = "../".trim($uploaded_image_path);
    $md5  = md5($base_file_name);

    for($i = 0; $i < 6; $i++) {
      $path = $path . $md5[$i] . '/';
      if(!is_dir($path)) {
        @mkdir($path);
        @chmod($path, 0777);
      }
    }

    $full_path = $path.$base_file_name.'.'.$file_extension;
    $path_only = str_replace("../media/","",$path);

    if (move_uploaded_file($_FILES['vcard']['tmp_name'], $full_path)) {
      $file_res = array(
        'at_s3' => '0',
        'file'  => $path_only.$file_name
      );
      $meta_param_dom['vcard'] = json_encode($file_res);
    }
  }
} else {
  if (isset($_REQUEST['vcard_path']) && !empty($_REQUEST['vcard_path'])) {
    $file_res = array(
      'at_s3' => '0',
      'file'  => $_REQUEST['vcard_path']
    );
    $meta_param_dom['vcard'] = json_encode($file_res);
  }
}
#------------------------------
# END:  Save vcard
#------------------------------


$meta->save_multiple_page_meta($page_id, $meta_param_dom, $module_type);

if(isset($_REQUEST['banner_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['banner_image'], 'banner_image');
}

if(isset($_REQUEST['featured_image'])) {
  $page_media_obj->save_image($focus->id, $_REQUEST['featured_image'], 'featured_image');
}

if(!$is_ajax_request) {
  $return_url = "./?module=OurTeam&action=EditView&id=$page_id";
  redirect($return_url);
}
else {
  echo json_encode(['status'=>'success', 'message'=>'Updated successfully.']);
}