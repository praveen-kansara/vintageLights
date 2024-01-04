<?php

if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");

class Menu extends awBean {

  var $log;
  var $object_name = "Menu";
  var $table_name  = "menu";

  var $id;
  var $name;
  var $description;
  var $date_created;
  var $date_modified;
  var $deleted;

  var $column_fields = [
    "id",
    "name",
    "description",
    "date_created",
    "date_modified",
    "deleted"
  ];

  var $list_fields = [
    "id",
    "name",
    "description",
    "date_created",
    "date_modified",
    "deleted"
  ];

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("Menu");
  }
  
  function get_menu_by_name($menu_name) {
    global $db;

    $sql = "SELECT id, name 
            FROM $this->table_name
            WHERE deleted = 0 
              AND name = '$menu_name' 
            LIMIT 1";
    $menu = $db->get_row($sql);
    return $menu;

  }

  function get_all_menu() {

    global $db;

    $query = "SELECT id, name
              FROM $this->table_name
              WHERE deleted = 0";

    $menu_data = $db->get_results($query);

    return $menu_data;

  }

  function delete_menu($menu_id) {
    global $db;
    $query = "DELETE FROM $this->table_name WHERE id = '$menu_id'";
    $db->query($query);
  }

}
