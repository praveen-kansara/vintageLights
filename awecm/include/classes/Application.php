<?php

if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");

class Application extends awBean {

  var $log; // the logger

  var $object_name = "Application"; // The object name...
  var $table_name  = "application"; // The table name 

  // stored values for Enquiry 
  var $id;
  var $name;
  var $email;
  var $filename;
  var $path;
  var $tag;
  var $mail_sent;
  var $post_data;
  var $date_created;
  var $date_modified;
  // Map other Enquiry specific vars here..

  var $column_fields = array("id"
    ,"type"
    ,"name"
    ,"email"
    ,"filename"
    ,"path"
    ,"tag"
    ,"mail_sent"
    ,"post_data"
    ,"date_created"
    ,"date_modified"
  );

  var $list_fields = array("id"
  ,"type"
  ,"name"
  ,"email"
  ,"filename"
  ,"path"
  ,"tag"
  ,"mail_sent"
  ,"post_data"
  ,"date_created"
  ,"date_modified"
  );

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("Application");
  }

}