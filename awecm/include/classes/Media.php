<?php

if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");

class Media extends awBean {

  var $log;
  var $object_name = "Media";
  var $table_name  = "media";

  var $id;
  var $name;
  var $path;
  var $attributes;
  var $at_s3;
  var $date_created;
  var $date_modified;
  var $deleted;

  var $column_fields = [
    "id",
    "name",
    "path",
    "attributes",
    "at_s3",
    "date_created",
    "date_modified",
    "deleted"
  ];

  var $list_fields = [
    "id",
    "name",
    "path",
    "attributes",
    "at_s3",
    "date_created",
    "date_modified",
    "deleted"
  ];

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("Media");
  }

  # Returns all the available media from media table
  function get_media_attachments($limit=1000, $offset=0, $where ="") {

    global $db, $max_entries_media_panel;

    $sql_limit  = isset($limit)  ? " LIMIT $limit": $max_entries_media_panel;
    $sql_offset = !empty($offset) ? ",$offset": "";
    $where_clause = !empty($where) ? "WHERE  $where ": "";

    $select_fields = implode($this->list_fields, ",");

    $query = "SELECT $select_fields
              FROM $this->table_name
					    $where_clause
              ORDER BY date_created DESC
              $sql_limit $sql_offset";

    return  $db->get_results($query, ARRAY_A);

  }

  function get_media_by_id ($id) {

    global $db;

    $select_fields = implode($this->list_fields, ",");

    $query = "SELECT $select_fields
              FROM $this->table_name
					    WHERE id = '$id'";

    return  $db->get_results($query, ARRAY_A);

  }

  function get_page_media_by_id_tag($page_id, $tag) {

    global $db;

    $select_fields = implode($this->list_fields, ",");

    $query = "SELECT $select_fields
					    FROM $this->table_name
					    WHERE page_id = '".$page_id."' AND tag='".$tag."' AND deleted <> 1";

    $page_media = $db->get_row($query, ARRAY_A);

    return $page_media;

  }

  function get_image_name_counts($image_name) {
    global $db;
    $sql = "SELECT count(id) as cnt
            FROM $this->table_name
            WHERE name LIKE '".$image_name."%'";
    return $rs = $db->get_var($sql);
  }

  function get_nons3_images($limit=1000) {

    global $db;
    $query = "SELECT id, path, name
              FROM media
              WHERE deleted = 0 
              AND at_s3 = 0
              ORDER BY date_created DESC LIMIT $limit";
    return $db->get_results($query, ARRAY_A);

  }

  function update_s3_flag($row_id, $flag) {

    $obj_media = new self();
    $obj_media->retrieve($row_id);
    $obj_media->at_s3 = $flag;
    $obj_media->save();
    
  }

  function get_media_by_name($image_name) {

    global $db;
    
    $q = "SELECT at_s3
			    FROM $this->table_name
			    WHERE name = '".$image_name."'";
		
    return $db->get_row($q);

  }

  function get_media_id($post_type, $table_name='') {

    global $db;

    if(!empty($table_name)) {
      $q = "SELECT id
            FROM $table_name WHERE deleted = '0'";
    } else {

      $q = "SELECT id
            FROM page
            WHERE post_type = '".$post_type."' AND deleted = 0";
    }

    $page_res =  $db->get_results($q, ARRAY_A);


    if(!empty($page_res)) {

      $post_id_array = array();

      foreach ($page_res as $k => $v) {
        $post_id_array[] = $v['id'];
      }

      $in_str = implode("','", $post_id_array);

      $q = "SELECT media_id
            FROM page_media
            WHERE page_id IN('$in_str')";

      $media_res =  $db->get_results($q, ARRAY_A);

      $media_id_array = array();

      if(!empty($media_res)) {
        foreach ($media_res as $media_key => $media_val) {
          if($media_val['media_id']) {
            $media_id_array[] = $media_val['media_id'];
          }
        }

        return $media_id_array; 
      }
    }

  }




}
