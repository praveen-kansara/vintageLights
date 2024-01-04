<?php

if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");

require_once("include/classes/Meta.php");

class Page extends awBean {

  var $log;
  var $object_name = "Page";
  var $table_name  = "page";

  var $id;
  var $title;
  var $slug;
  var $sequence;
  var $short_description;
  var $content;
  var $parent_post_id;
  var $post_type;
  var $status;
  var $visibility;
  var $visibility_password;
  var $tag;
  var $uri;
  var $location;
  var $created_user_id;
  var $modified_user_id;
  var $date_created;
  var $date_modified;
  var $deleted;
  var $publish_date;

  var $column_fields = [
    "id",
    "title",
    "slug",
    "sequence",
    "short_description",
    "content",
    "parent_post_id",
    "post_type",
    "status",
    "visibility",
    "visibility_password",
    "tag",
    "uri",
    "location",
    "created_user_id",
    "modified_user_id",
    "date_created",
    "date_modified",
    "deleted",
    "publish_date"
  ];
  
  var $list_fields = [
    "id",
    "title",
    "slug",
    "sequence",
    "short_description",
    "content",
    "parent_post_id",
    "post_type",
    "status",
    "visibility",
    "visibility_password",
    "tag",
    "uri",
    "location",
    "created_user_id",
    "modified_user_id",
    "date_created",
    "date_modified",
    "deleted",
    "publish_date"
  ];

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("Page");
  }

  function generate_list_query($where = "", $order_by = "", $offset = 0, $limit = "") {

    $select_fields = implode($this->list_fields, ",");

    $q = "SELECT $select_fields
            FROM $this->table_name";

    if($where != "") {
      $q .= " WHERE $where";
    }
    
    if($order_by && $order_by != "") {
      $q .= " ORDER BY $order_by";
    }

    if($offset != 0 && !empty($limit)) {
      $q .= " LIMIT $offset, $limit";
    } else if($limit != '') $q .= " LIMIT $limit";

    return $q;

  }

  function get_page_by_uri($uri) {

    global $db;
    $select_fields = implode( ",",$this->list_fields);

    if($uri) {
      
      $uri_without_slash = rtrim($uri, "/");

      $qry = "SELECT $select_fields
              FROM $this->table_name
              WHERE (uri = '$uri' OR uri = '$uri_without_slash')
               AND deleted = 0
               AND status = 'published'
              LIMIT 1";
      return $db->get_row($qry);

    }

  }


  function get_page_by_condition($where = array()) {

    global $db;
    $select_fields = implode($this->list_fields, ",");

    $where_arr = array();
    $where_arr = $where;

    $where_arr[] = "deleted = 0";
    $where_arr[] = " status = 'published'";

    $where_str = build_where_clause($where_arr);

    if($where_str) {

      $qry = "SELECT $select_fields
              FROM $this->table_name
              WHERE $where_str
              LIMIT 1";
      return $db->get_row($qry);

    }

  }

  function get_homepage() {

    global $db;

    $qry = "SELECT pg.id, pg.content, pg.title
            FROM $this->table_name as pg
            WHERE pg.status = 'published'
             AND pg.tag LIKE '%home_page_template%'
             AND  pg.deleted = 0
            LIMIT 1";

    $content = $db->get_row($qry);

    return  $content;

  }

  function get_pages_by_post_type($post_type, $order_by="post_type", $is_published='', $limit='') {

    global $db;

    $published_str = '';
    if($is_published == '1') {
      $published_str = " AND  status = 'published'";
    }

    $qry = "SELECT id, title as name, uri
            FROM $this->table_name
            WHERE post_type = '$post_type'
              ".$published_str."
               AND  deleted = 0
            ORDER BY $order_by";

    $pages = $db->get_results($qry);

    return $pages;

  }

  function get_post_data($post_type = 'page', $tag = 'featured_image', $limit = 10, $order_by = '(sequence)*1 ASC') {

    global $db;
    $media_details = array();
    $projects[0]['media'] = array();

    $qry = "SELECT id, title as name, uri, slug, short_description, content, date_created
            FROM $this->table_name
            WHERE post_type   = '$post_type'
            AND  status = 'published'
            AND  deleted = 0
            ORDER BY $order_by";
            if($limit){
              $qry .= " LIMIT $limit";
            }

    $projects = $db->get_results($qry, ARRAY_A);

    foreach ($projects as $key => $service) {

      $page_id = $service['id'];

      $media_query = "SELECT media.name, media.path, media.at_s3, media.attributes
                        FROM media
                        INNER JOIN page_media
                        ON media.id = page_media.media_id
                        WHERE page_media.page_id = '$page_id' AND tag LIKE '%$tag%'";

      $media_details = $db->get_row($media_query, ARRAY_A);

      if(!empty($media_details) && count($media_details)>0) {
        $projects[$key]['media'] = $media_details;
      }
      else {
        $projects[$key]['media'] = [];
      }

    }

    return $projects;
  }

  function parse_short_code($short_code = '', $type ='') {

    global $short_code_dom, $db;
    $content = '';

    if(!empty($type) && $type == 'widget') {

      $short_code = str_replace(array( '{', '}' ), '', $short_code);

      $qry = "SELECT $this->table_name.content, meta.meta_value
              FROM $this->table_name
              INNER JOIN meta
              ON $this->table_name.id = meta.module_id
              WHERE $this->table_name.post_type  = 'widget'
              AND  meta.meta_value = '{$short_code}'
              AND  $this->table_name.status = 'published'
              AND  $this->table_name.deleted = 0
              AND  meta.module_type = 'widget'
              LIMIT 1";

      $widgets_rs = $db->get_results($qry, ARRAY_A);
      if(!empty($widgets_rs[0]['content'])) {
        $content = $widgets_rs[0]['content'];
      }

    } else if(!empty($short_code_dom[$short_code])) {
      # Create dynamic function
      $content = $short_code_dom[$short_code]();
    }

    return $content;

  }

  function process_short_codes($content) {

    global $short_code_dom, $db;

    # Processing array short codes
    foreach ($short_code_dom as $replace => $function) {
      $function_name = $function;
      $content = str_replace($replace, $function_name(), $content);
    }

    ## update - should we place this code above the short code dom processing
    # processing database short codes    
    $qry = "SELECT $this->table_name.content, meta.meta_value
            FROM $this->table_name
            INNER JOIN meta
              ON $this->table_name.id = meta.module_id
            WHERE $this->table_name.post_type  = 'widget'
              AND  $this->table_name.status = 'published'
              AND  $this->table_name.deleted = 0
              AND  meta.module_type = 'widget'";

    $widgets = $db->get_results($qry, ARRAY_A);

    foreach($widgets as $short_code) {
      $short_ = "{".$short_code['meta_value']."}";
      $content = str_replace($short_ , $short_code['content'], $content);
    }

    return $content;
  }

  function get_post_types($exclude_list="") {
    
    global $db;
    
    $where = "";

    if($exclude_list!="") {
      $where = "WHERE post_type NOT IN ($exclude_list) AND deleted = 0";
    }

    $qry = "SELECT DISTINCT post_type
            FROM $this->table_name
            $where 
            ORDER BY post_type";

    $post_types = $db->get_results($qry, ARRAY_A);

    return $post_types;

  }

  //Retrieving all properties
  function get_all_properties($limit = 100, $where = "", $image_type = array()) {
    $property_images = array();

    global $db, $site_url;

    $whr = '';

    if(!empty($where)) {
      $whr .= " AND {$where} ";
    } 

    $sql = "SELECT id, title as name, content, short_description, content, uri, slug, location
            FROM $this->table_name
            WHERE deleted = 0
              AND status = 'published'
              AND post_type = 'property'
              {$whr} 
            ORDER BY (sequence * 1) ASC
            LIMIT {$limit}";

    $rs = $db->get_results($sql, ARRAY_A);

    foreach ($rs as $key => $row) {

      $page_id = $row['id'];

      if(empty($image_type)) {
        $image_where = "(tag LIKE '%featured_image%' or tag LIKE '%property_image%')";
      } else {
        $image_where = "tag IN ('".implode("','", $image_type)."')";
      }

      $media_query = "SELECT media.name, media.path, media.at_s3, page_media.tag, 
                             media.attributes
                      FROM media
                      INNER JOIN page_media
                        ON media.id = page_media.media_id
                      WHERE page_media.page_id = '{$page_id}'
                        AND {$image_where}
                        AND page_media.deleted = 0
                        AND media.deleted = 0
                      ORDER BY page_media.sequence*1 DESC";

      $media_details = $db->get_results($media_query, ARRAY_A);

      $property_images = [];
      $rs[$key]['icon'] = [];
      $rs[$key]['property_images'] = [];

      if(count($media_details)>0) {

        foreach ($media_details as  $media_row) {

          if($media_row['tag'] == "featured_image") {
            $rs[$key]['icon'] = $media_row;
          } else {
            $property_images[] = $media_row;
          }
        }
        $rs[$key]['property_images'] = $property_images;
      }
    }
    return $rs;

  }

  # Purpose: Retrieve post type page uri
  function check_post_type_by_uri($current_full_url) {
    global $db;
    $uri_without_slash = rtrim($current_full_url, "/");

    $sql = "SELECT 'page' AS type 
            FROM page
            WHERE (uri = '$current_full_url')";

    $uri = $db->get_row($sql);

    if($uri && $uri->type) return $uri->type;
    else return false;

  }

  function create_sitemap() {

    global $db;
    $get_pages = new self();
    $pages = $get_pages->get_all_pages_list();

    $query = "SELECT max(date_modified) AS last_modified
              FROM $this->table_name
              WHERE post_type IN ('page', 'property')
                AND  status  = 'published'
                AND  deleted = 0
              ORDER BY last_modified DESC LIMIT 1";

    $lastupdated = $db->get_row($query, ARRAY_A);

    $sitemap      = array();
    $page_data    = array();;
    $property_data = array();

    if($pages) {

      foreach ($pages as $page) {

        if($page->post_type == 'page') {

          $page_data[] = $page->uri;
          $sitemap['sitemap_page'] = array('post_type'      => 'page',
                                           'url'            =>  $page_data, 
                                           'page_frequency' => 'daily',
                                           'page_priority'  => '1.0'
                                          );

        } else if($page->post_type == 'property') {

          $sitemap['sitemap_property'] = array( 'post_type'     => 'property',
                                               'url'            =>  $property_data, 
                                               'page_frequency' => 'daily',
                                               'page_priority'  => '1.0'
                                          );

        }
      }

      $sitemap['main_sitemap'] = array('post_type'  => 'file',
                                        'last_modified' => $lastupdated['last_modified'],
                                        'url' => array( "page"     => "sitemap-page.xml",
                                                        "property" => "sitemap-property.xml",
                                                      )
                                        );
          
    }

    return $sitemap;
  }

  # function to get all types of pages & posts
  function get_all_pages_list() {
    global $db;
    $qry = "SELECT id, title as name, post_type, uri
            FROM $this->table_name
            WHERE post_type  IN ('page', 'property')
              AND  status = 'published'
              AND  deleted = 0
            ORDER BY post_type";
    $pages = $db->get_results($qry);
    return $pages;
  }

  # function to get all location pages 
  function get_location_pages() {
    global $db;
    $qry = "SELECT id, title AS name, uri
            FROM $this->table_name
            WHERE post_type  IN ('page')
              AND status = 'published'
              AND location != ''
              AND deleted = 0";
    $pages = $db->get_results($qry);
    return $pages;
  }

  //Retrieving all faq
  function get_all_faq($limit = 100, $where = "") {
    $property_images = array();

    global $db, $site_url;

    $whr = '';

    if(!empty($where)) {
      $whr .= " AND {$where} ";
    } 

    $sql = "SELECT id, title, content, short_description, uri, slug
            FROM $this->table_name
            WHERE deleted = 0
              AND status = 'published'
              AND post_type = 'faq'
              {$whr} 
            ORDER BY (sequence * 1) ASC
            LIMIT {$limit}";

    $rs = $db->get_results($sql, ARRAY_A);
    return $rs;
  }

  /***
   * Fetches an array of next and prev posts to create links in the BlogDetail action
   */
  function get_next_prev_posts($date_published, $post_type = 'blog') {
    global $db;
  
    $sql = "SELECT id, title, short_description, uri, slug
            FROM $this->table_name WHERE 
              deleted = 0 AND 
              status = 'published' AND
              publish_date < '$date_published' AND
              post_type = '$post_type'
            ORDER BY publish_date DESC 
            LIMIT 1";
    $ret['prev'] =  $db->get_row($sql, ARRAY_A);
    
    $sql = "SELECT id, title, short_description, uri, slug
            FROM $this->table_name WHERE 
              deleted = 0 AND 
              status = 'published' AND
              publish_date > '$date_published' AND
              post_type = '$post_type'
            ORDER BY publish_date ASC
            LIMIT 1";
    $ret['next'] =  $db->get_row($sql, ARRAY_A);

    return $ret;
  
  }

}


