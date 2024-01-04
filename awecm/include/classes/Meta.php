<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");
require_once("include/utils.inc.php");
require_once("include/classes/PageMedia.php");

class Meta extends awBean {

  var $log;
  var $object_name = "Meta";
  var $table_name  = "meta";

  var $id;
  var $module_id;
  var $module_type;
  var $meta_key;
  var $meta_value;
  var $date_created;
  var $date_modified;
  var $deleted;

  var $column_fields = [
    "id",
    "module_id",
    "module_type",
    "meta_key",
    "meta_value",
    "date_created",
    "date_modified",
    "deleted"
  ];

  var $list_fields = [
    "id",
    "module_id",
    "module_type",
    "meta_key",
    "meta_value",
    "date_created",
    "date_modified",
    "deleted"
  ];

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("Meta");
  }

  function get_page_meta_by_key($module_id, $meta_key) {

    global $db;

    $query = "SELECT id, meta_key, meta_value
              FROM meta
              WHERE module_id = '$module_id'
                AND meta_key = '$meta_key'
                AND deleted <> 1 LIMIT 1";

    $meta_data = $db->get_row($query, ARRAY_A);

    return $meta_data;

  }

  function save_page_meta($module_id, $meta_key, $meta_value, $module_type="") {

    global $db;

    $q = "SELECT id FROM $this->table_name
          WHERE module_id  = '$module_id'
          AND meta_key = '$meta_key'
          AND deleted  = 0
          LIMIT 1 ";

    $row_id = $db->get_var($q);

    $self = new self();

    if($row_id) $self->retrieve($row_id);

    $self->module_id  = $module_id;
    $self->meta_key   = $meta_key;
    $self->meta_value = $meta_value;
    $self->module_type = $module_type; 

    $self->save();

  }

  function save_multiple_page_meta($module_id, $meta_arr = array(), $module_type="") {

    global $db;

    $meta_keys_str = implode("','", array_keys($meta_arr));

    $sql = "SELECT id, module_type, meta_key, meta_value 
            FROM $this->table_name
            WHERE module_id  = '$module_id'
              AND module_type = '$module_type'
              AND meta_key IN ('$meta_keys_str')
              AND deleted  = 0";

    $meta_rows_data = objectToArray($db->get_results($sql));

    if(empty($meta_rows_data)) {

      foreach ($meta_arr as $key => $value) {

        $self = new self(); 
        $self->module_id   = $module_id;
        $self->module_type = $module_type;
        $self->meta_key    = $key;
        $self->meta_value  = $value;
        $self->save();
      }

    } else {

      $existing_meta_key = array();
      foreach ($meta_rows_data as $row_key => $row_data) {

        $self = new self();


        if(!empty($row_data['id'])) $self->retrieve($row_data['id']);
        if(isset($meta_arr[$row_data['meta_key']])) {
          $existing_meta_key[] = $row_data['meta_key'];
          $self->meta_value = $meta_arr[$row_data['meta_key']];
          $self->save();
        }
      }

      #Saving the meta values that are not existing in the DB
      if(!empty($existing_meta_key)) {
        foreach ($meta_arr as $k => $v) {
          if(!in_array($k, $existing_meta_key)) {
            $self = new self(); 
            $self->module_id   = $module_id;
            $self->module_type = $module_type;
            $self->meta_key    = $k;
            $self->meta_value  = $v;
            $self->save();
          }
        }
      }
    }
  }

  function get_all_page_meta($module_id) {

     global $db;

     ## update - can we use retrieve by name function to get this done. Check once.
     $qry = "SELECT meta_key, meta_value
             FROM   $this->table_name
             WHERE module_id = '$module_id'
             AND   deleted = 0";

     $meta_data = $this->db->get_results($qry);

     $page_meta_array = array();

     if($meta_data) {
        foreach($meta_data as $meta) {
        $page_meta_array[$meta->meta_key] = $meta->meta_value;
        }

     }

     return $page_meta_array;

  }

  function manage_meta_images($post_type, $page_id) {

    global $media_path_site, $media_path_s3, $featured_image_default_img,
            $uploaded_image_path, $site_url;

    $img_url = $social_image = '';

    switch($post_type) {

      case 'page':
      $social_image = "featured_image";
      break;

      case 'category':
      $social_image = "category_image";
      break;

      case 'product':
      $social_image = "featured_image";
      break;

      default: 'featured_image';

    }

    $PageMediaObj = new PageMedia();
    $get_image = $PageMediaObj->get_page_image($page_id, $social_image);

    $img_url = createSiteImageWithSize($get_image, "795x400");

    return $img_url;

  }

  function page_meta_via_uri($current_full_url, $post_type) {

    global $db, $site, $site_url, $domain_name, $uploaded_image_path;

    $meta_values = array();
    $meta_title = $meta_keyword = $meta_description = $meta_img = '';
    
    $uri_without_slash = rtrim($current_full_url, "/");

    $qry = "SELECT me.meta_key, me.meta_value, pg.id as page_id, pg.post_type, 
            pg.title as page_title
            FROM   $this->table_name as me
            INNER JOIN page as pg on pg.id = me.module_id
            WHERE (pg.uri = '$current_full_url' OR pg.uri = '$uri_without_slash')
            AND pg.deleted = 0
            AND pg.status = 'published'";

    $meta_data  = $this->db->get_results($qry);

    $site_name  = !empty($site->site_name) ?  $site->site_name : '';

    # Get default meta
    $meta_values[] = '<meta charset="utf-8">';
    $meta_values[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    $meta_values[] = '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">'."\n"; 

    # Get Dynamic meta values
    if(!empty($meta_data)) {

      $robot_data['index']  = 'index';
      $robot_data['follow'] = 'follow';

      $page_id    = $meta_data[0]->page_id;
      $meta_title = $meta_data[0]->page_title;

      $meta_obj = new self();
      $meta_img = $meta_obj->manage_meta_images($post_type, $page_id);


      foreach($meta_data as $meta) {
        if($meta->meta_key == 'meta_title'         && !empty($meta->meta_value)) { $meta_title = $meta->meta_value; }
        if($meta->meta_key == 'meta_keywords'      && !empty($meta->meta_value)) { $keyword = $meta->meta_value; }
        if($meta->meta_key == 'meta_description'   && !empty($meta->meta_value)) { $description = $meta->meta_value;}
        if($meta->meta_key == 'meta_robots_index'  && $meta->meta_value == 1) $robot_data['index']  = 'noindex';
        if($meta->meta_key == 'meta_robots_follow' && $meta->meta_value == 1) $robot_data['follow'] = 'nofollow';
      }

      if(!empty($meta_title))  $meta_values[] = '<title>'.$meta_title.'</title>';
      if(!empty($keyword))     $meta_values[] = '<meta name="keywords" content="'.$keyword.'">';
      if(!empty($description)) $meta_values[] = '<meta name="description" content="'.$description.'">';

      $robot_meta = "\n".'<meta name="robots" content="'.$robot_data['index'].', '.$robot_data['follow'].'">'."\n";
      array_push($meta_values, $robot_meta);

    }

    $meta_values[] = '<meta typeof="og:article" content="website" />'."\n"; 
    $meta_values[] = '<meta property="og:type" content="website" />'."\n"; 
    $meta_values[] = '<meta property="og:title" content="'.$meta_title.'" />';
    $meta_values[] = '<meta property="og:url" content="'.$current_full_url.'" />';

    $is_flash_page = strpos($current_full_url, "flash-sale");

    if(!empty($meta_img) && !$is_flash_page) {
      $meta_values[] = '<meta property="og:image:width" content="795"/>';
      $meta_values[] = '<meta property="og:image:height" content="400"/>';
      $meta_values[] = '<meta property="og:image" content="'.$meta_img.'" />';

    } else if($is_flash_page !== false){

      // Added static image for flash sale
      $meta_values[] = '<meta property="og:image:width" content="795"/>';
      $meta_values[] = '<meta property="og:image:height" content="400"/>';
      $meta_values[] = '<meta property="og:image" content="http://urbanoffice.local/media/d/e/e/a/4/9/fb-flashsale-795x400.jpg" />';
    }

    $meta_values[] = '<meta property="og:site_name" content="'.$site_name.'" />';
    if(!empty($description)) $meta_values[] = '<meta property="og:description" content="'.$description.'"/>';
    $meta_values[] = '<meta name="twitter:site" content="">';
    $meta_values[] = '<meta name="twitter:creator" content="">';
    $meta_values[] = '<meta name="twitter:card" content="summary_large_image">';
    $meta_values[] = '<meta name="twitter:url" content="'.$current_full_url.'">';
    $meta_values[] = '<meta name="twitter:domain" content="'.$domain_name.'">';
    $meta_values[] = '<meta name="twitter:title" content="'.$meta_title.'">';

    if(!empty($description))$meta_values[] = '<meta name="twitter:description" content="'.$description.'">';

    if(!empty($meta_img)) {
      $meta_values[] = '<meta name="twitter:image" content="'.$meta_img.'">';
    }

    $meta_values[] = "\n".'<link rel="canonical" href="'.$current_full_url.'" />'."\n";

    return $meta_values;

  }

  # Purpose: To get the meta info in raw form
  function page_meta_info_via_uri($current_full_url, $post_type) {

    global $db, $site, $site_url, $domain_name, $uploaded_image_path;

    $meta_values = array();
    $return_arr = array();
    $meta_title = $meta_keyword = $meta_description = $meta_img = '';
    
    $uri_without_slash = rtrim($current_full_url, "/");

    if($post_type == 'product') {

      $qry = "SELECT me.meta_key, me.meta_value, pro.id as page_id,
                pro.name as page_title
                FROM   $this->table_name as me
                INNER JOIN product as pro on pro.id = me.module_id
                WHERE (pro.uri = '$current_full_url' OR pro.uri = '$uri_without_slash')
                AND pro.deleted = 0
                AND pro.status = 'published'";

    } else if($post_type == 'category') {

      $qry = "SELECT me.meta_key, me.meta_value, cate.id as page_id,
                cate.name as page_title
                FROM   $this->table_name as me
                INNER JOIN product_category as cate on cate.id = me.module_id
                WHERE (cate.uri = '$current_full_url' OR cate.uri = '$uri_without_slash')
                AND cate.deleted = 0
                AND cate.status = 'published'";

    } else {
      $qry = "SELECT me.meta_key, me.meta_value, pg.id as page_id, pg.post_type, 
              pg.title as page_title
              FROM   $this->table_name as me
              INNER JOIN page as pg on pg.id = me.module_id
              WHERE (pg.uri = '$current_full_url' OR pg.uri = '$uri_without_slash')
              AND pg.deleted = 0
              AND pg.status = 'published'";
    }

    $meta_data  = $this->db->get_results($qry);

    if(!empty($meta_data)) {
      foreach ($meta_data as $meta_cnt => $meta_data) {
        $return_arr[$meta_data->meta_key] = $meta_data->meta_value;
      }
    }

    return $return_arr;
  }


  function check_widget_short_code_counts($short_code, $module_id = "") {

    global $db;
    $where = "";

    if($module_id != "") {
      $where = "AND module_id <> '$module_id'";
    }

    $q = "SELECT count(id) as cnt
            FROM $this->table_name
            WHERE module_type = 'widget'
            AND meta_key = 'short_code'
            AND deleted = 0
            AND meta_value LIKE '%".$short_code."%'
            $where";

    return $rs = $db->get_var($q);

  }

}
