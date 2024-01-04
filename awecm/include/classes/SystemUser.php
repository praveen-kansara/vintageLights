<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");

class SystemUser extends awBean {

  var $log;
  var $object_name = "SystemUser";
  var $table_name  = "system_user";

  var $id;
  var $email;
  var $password;
  var $name;
  var $phone;
  var $city;
  var $address;
  var $is_active;
  var $date_created;
  var $date_modified;
  var $deleted;


  var $column_fields = [
    "id",
    "email",
    "password",
    "name",
    "phone",
    "city",
    "address",
    "is_active",
    "date_created",
    "date_modified",
    "deleted"
  ];

  var $list_fields = [
    "id",
    "email",
    "password",
    "name",
    "phone",
    "city",
    "address",
    "is_active",
    "date_created",
    "date_modified",
    "deleted"
  ];

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("SystemUser");
  }

  function authenticate_user($email, $password, $rememberme="") {

    global $db;

    $user_password = md5($password);

    $query = "SELECT id, name FROM $this->table_name
    WHERE email = '$email'
    AND password = '$user_password'
    AND is_active = 1
    AND deleted   = 0
    LIMIT 1";
    $user_rs = $db->get_row($query);

    if(empty($user_rs)) {
      return false;

    }
    else {
      $_SESSION['current_user_id'] = $user_rs->id;
      $_SESSION['current_user_name'] = $user_rs->name;

      /* Remember Me functionality */
      if($rememberme == '1') {
        $cookie_time = 3600 * 24 * 30;
        setcookie("urbanoffice_auth_key", $user_rs->id, time()+$cookie_time);
      }
      return true;
    }

  }

  function check_duplicate_email($user_id='', $email_id) {

    global $db;

    if($user_id) {

      $query = "SELECT id FROM $this->table_name
      WHERE  email = '$email_id'
      AND    id != '$user_id'
      AND deleted = 0 ";

      $user = $db->get_row($query, ARRAY_A);

      return $user ? TRUE : FALSE;

    } 
    else {

      $query = "SELECT id FROM $this->table_name
      WHERE  email = '$email_id'
      AND deleted = 0";

      $user = $db->get_row($query, ARRAY_A);

      return $user ? true : false;

    }

  }


  function get_user_dom() {

    global $db;

    $query = "SELECT id, name FROM $this->table_name
    WHERE is_active = '1'
    AND deleted   = 0";
    $user_rs = $db->get_results($query);

    if(empty($user_rs)) {
      return false;
    }
    else {

      foreach ($user_rs as $usr_key => $usr_val) {
        $user_dom[$usr_val->id] = $usr_val->name;
      }
      return $user_dom;
    }

  }


  function get_user_details($where = array(), $limit = 1) {

    global $db;

    $where_arr   = array();
    $user_rs = '';
    $where_arr   = $where;
    $where_arr[] = "deleted = 0";

    if(!empty($where_arr)) {

      $where_str = build_where_clause($where);

      $query = "SELECT * FROM $this->table_name
      WHERE $where_str LIMIT $limit";

      $user_rs = $db->get_results($query);

      return $user_rs;
    }


  }

}
