<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");

class Redir extends awBean {

  var $log;
  var $object_name = "Redir";
  var $table_name  = "redir";

  var $id;
  var $uri;
  var $redir;
  var $redir_type;
  var $count;
  var $date_created;
  var $date_modified;
  var $modified_user_id;
  var $deleted;


  var $column_fields = [
    "id",
    "uri",
    "redir",
    "redir_type",
    "count",
    "date_created",
    "date_modified",
    "modified_user_id",
    "deleted"
  ];

  var $list_fields = [
    "id",
    "uri",
    "redir",
    "redir_type",
    "count",
    "date_created",
    "date_modified",
    "modified_user_id",
    "deleted"
  ];

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("Redir");
  }



}
