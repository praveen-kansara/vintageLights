<?php

if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");

class PageMedia extends awBean {

  var $log;
  var $object_name = "PageMedia";
  var $table_name  = "page_media";

  var $id;
  var $page_id;
  var $media_id;
  var $tag;
  var $sequence;
  var $date_created;
  var $date_modified;
  var $deleted;

  var $column_fields = [
    "id",
    "page_id",
    "media_id",
    "tag",
    "sequence",
    "date_created",
    "date_modified",
    "deleted"
  ];

  var $list_fields = [
    "id",
    "page_id",
    "media_id",
    "tag",
    "sequence",
    "date_created",
    "date_modified",
    "deleted"
  ];

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("PageMedia");
  }

  function get_page_media_by_id_tag($page_id, $tag) {

    global $db;

    $select_fields = implode($this->list_fields, ",");

    $query = "SELECT $select_fields
              FROM $this->table_name
              WHERE page_id = '".$page_id."' and tag='".$tag."' and deleted <> 1";

    $page_media = $db->get_row($query, ARRAY_A);

    return $page_media;

  }

  function get_page_image($page_id, $tag) {

    global $db;
    $page_media = array();

    $query = "SELECT media.path, page_media.media_id, media.name, media.at_s3, media.attributes
              FROM page_media
              INNER JOIN media
              ON page_media.media_id = media.id
              WHERE page_media.page_id = '".$page_id."' and page_media.tag='".$tag."' and page_media.deleted = 0 and page_media.media_id <> '' LIMIT 1";

    $page_media = $db->get_row($query, ARRAY_A);

    return $page_media;

  }

  function get_page_images($page_id, $tag, $limit = '', $order_by = '') {

    global $db;

    $query = "SELECT media.path, page_media.media_id, media.name, media.at_s3, media.attributes
              FROM page_media
              INNER JOIN media
              ON page_media.media_id = media.id
              WHERE page_media.page_id = '".$page_id."' and page_media.tag='".$tag."' and page_media.deleted = 0 and page_media.media_id <> ''
               AND page_media.deleted = 0
               AND media.deleted = 0";

    if($order_by != '') $query .= ' ORDER BY '.$order_by;

    if($limit != '') {
      $query .= " LIMIT $limit";
    }

    $page_media = $db->get_results($query, ARRAY_A);

    return $page_media;

  }

  function save_image($page_id, $banner_image_id, $tag) {

    /*
    * Need to study how to manage if multiple tags in the tag column.
    */

    global $db;

    $q = "SELECT id FROM $this->table_name
          WHERE page_id  = '$page_id'
          AND tag = '$tag'
          AND deleted  = 0
          LIMIT 1 ";

    $row_id = $db->get_var($q);

    $objPageMeta = new self();

    if($row_id) $objPageMeta->retrieve($row_id);

    $objPageMeta->page_id = $page_id;
    $objPageMeta->tag = $tag;
    $objPageMeta->sequence = 1;
    $objPageMeta->media_id = $banner_image_id;

    $objPageMeta->save();

  }

  function get_image_ids_by_page_id($page_id, $name = '') {

    global $db;

    $where = array();

    if(!empty($page_id)) $where[] = " $this->table_name.page_id  = '$page_id'";

    if(!empty($name) || !empty($page_id)) {

      if(!empty($name)) $where[] = " m.name LIKE '%$name%'";

      $q = "SELECT $this->table_name.media_id as media_id FROM $this->table_name
            INNER JOIN media AS m ON m.id = $this->table_name.media_id";

      if(!empty($where)) {

        $where_clause  = build_where_clause($where);
        $q .= " WHERE $where_clause";
      }

      $q .=   "AND m.deleted = 0";

    } else {

      $q = "SELECT id as media_id FROM media";
      $where[] = " name LIKE '%$name%'";
      if(!empty($where)) {
        $where_clause  = build_where_clause($where);
        $q .= " WHERE $where_clause";
      }

      $q .= " AND deleted = 0";
    }

    $rs = $db->get_results($q, ARRAY_A);

    return $rs;

  }

  function save_multi_image($page_id, $banner_image_ids, $tag) {

    global $db;

    $q = "DELETE FROM $this->table_name
          WHERE page_id  = '$page_id'
          AND tag = '$tag'
          AND deleted  = 0";

    $db->query($q);
    
    $counter = 1;
    if($banner_image_ids) {

      foreach($banner_image_ids as $banner_image_id) {

        $objPageMeta = new self();

        $objPageMeta->page_id = $page_id;
        $objPageMeta->tag = $tag;
        $objPageMeta->sequence = $counter;
        $objPageMeta->media_id = $banner_image_id;

        $objPageMeta->save();
        $counter++;

      }

    }

  }

}
