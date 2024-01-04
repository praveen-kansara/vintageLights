<?php

if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");

class MenuItem extends awBean {

  var $log;
  var $object_name = "MenuItem";
  var $table_name  = "menu_item";

  var $id;
  var $name;
  var $parent_id;
  var $menu_id;
  var $type;
  var $page_id;
  var $url;
  var $display_sequence;
  var $created_user_id;
  var $modified_user_id;
  var $date_created;
  var $date_modified;
  var $deleted;

  var $column_fields = [
    "id",
    "name",
    "parent_id",
    "menu_id",
    "type",
    "page_id",
    "url",
    "display_sequence",
    "created_user_id",
    "modified_user_id",
    "date_created",
    "date_modified",
    "deleted"
  ];

  var $list_fields = [
    "id",
    "name",
    "parent_id",
    "menu_id",
    "type",
    "page_id",
    "url",
    "display_sequence"
  ];

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("MenuItem");
  }

  # returns all menu
  function get_all_menu() {

    global $db;

    $select_fields = implode($this->list_fields, ", mi.");
    if($select_fields) $select_fields = "mi.".$select_fields;

    $query = "SELECT $select_fields, page.slug, page.uri
              FROM $this->table_name as mi
              LEFT JOIN page ON page.id = mi.page_id
              WHERE mi.deleted = 0
              ORDER BY parent_id, display_sequence ASC ";

    return  $db->get_results($query, ARRAY_A);

  }

  function save_menu_itmes($menu_item) {
     
    global $db;
    
    $date_created = $date_modified = date('Y-m-d H:i:s');
    $created_user_id = $modified_user_id = $_SESSION['current_user_id'];

    $query = "INSERT INTO $this->table_name (id, name, parent_id, menu_id, type , page_id, url, display_sequence, 
              created_user_id, modified_user_id, date_created, date_modified)
              VALUES ('$menu_item[id]', '$menu_item[name]', '$menu_item[parent_id]', '$menu_item[menu_id]', '$menu_item[type]',
              '$menu_item[page_id]', '$menu_item[url]', '$menu_item[display_sequence]','$created_user_id', '$modified_user_id', '$date_created', '$date_modified'  )";
      
    return  $db->query($query);

  }

  function delete_menu($menu_id) {

    global $db;
    
    $query = "DELETE FROM $this->table_name
                WHERE menu_id = '$menu_id'";
      
    $db->query($query);

  }

  function delete_menu_and_meta($menu_id) {
    
    global $db;
    $query = "SELECT id from $this->table_name
                WHERE menu_id='$menu_id'";
    
    $menu_items = $db->get_results($query);

    if($menu_items) {
        foreach($menu_items as $menu_item) {
        $query = "DELETE FROM meta 
        WHERE module_id = '$menu_item->id' AND module_type = 'menu'";
        $db->query($query);
      }
    }
    
    $this->delete_menu($menu_id);

  }

}
