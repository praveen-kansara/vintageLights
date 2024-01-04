<?php

if(!defined('__URBAN_OFFICE__')) exit;

require_once("data/awBean.php");
require_once("include/logger.php");

class Enquiry extends awBean {

  var $log; // the logger

  var $object_name = "Enquiry"; // The object name...
  var $table_name  = "enquiry"; // The table name 

  // stored values for Enquiry 
  var $id;
  var $property_id;
  var $type;
  var $first_name;
  var $last_name;
  var $email;
  var $message;
  var $city;
  var $zip;
  var $country;
  var $no_of_people;
  var $move_in_date;
  var $tag;
  var $mail_sent;
  var $user_detail;
  var $date_created;
  var $date_modified;
  // Map other Enquiry specific vars here..

  var $column_fields = array("id"
    ,"type"
    ,"property_id"
    ,"first_name"
    ,"last_name"
    ,"phone"
    ,"email"
    ,"message"
    ,"city"
    ,"zip"
    ,"country"
    ,"no_of_people"
    ,"move_in_date"
    ,"tag"
    ,"mail_sent"
    ,"user_detail"
    ,"date_created"
    ,"date_modified"
  );

  var $list_fields = array("id"
    ,"type"
    ,"property_id"
    ,"first_name"
    ,"last_name"
    ,"phone"
    ,"email"
    ,"message"
    ,"city"
    ,"zip"
    ,"country"
    ,"no_of_people"
    ,"move_in_date"
    ,"tag"
    ,"mail_sent"
    ,"user_detail"
    ,"date_created"
    ,"date_modified"
  );

  function __construct() {
    awBean::__construct();
    $this->log = New Logger("Enquiry");
  }

  function get_enq_data($where = array(), $limit = '') {

    global $db;
    $enq_data = array();

    $where_clause  = build_where_clause($where);

    $qry = "SELECT id, first_name, last_name. email, message, phone, tag, date_created
                  ,move_in_date, no_of_people, property_id
            FROM  $this->table_name
            WHERE $where_clause";

    if($limit != '') {
      $qry .= " LIMIT $limit";
    }

    $response = $db->get_results($qry);

    if(!empty($response)) {
      foreach ($response as $k => $v) {

        if(!empty($v->id)) {
          $enq_data[$v->id]['id']           = $v->id;
          $enq_data[$v->id]['name']         = $v->first_name.' '.$v->last_name;
          $enq_data[$v->id]['email']        = $v->email;
          $enq_data[$v->id]['message']      = $v->message;
          $enq_data[$v->id]['phone']        = $v->phone;
          $enq_data[$v->id]['tag']          = $v->tag;
          $enq_data[$v->id]['date_created'] = $v->date_created;
          $enq_data[$v->id]['move_in_date'] = $v->move_in_date;
          $enq_data[$v->id]['no_of_people'] = $v->no_of_people;
          $enq_data[$v->id]['property_id']  = $v->property_id;
        }
      }
    }

    return $enq_data;
  }
}