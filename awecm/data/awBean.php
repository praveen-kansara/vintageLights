<?php

/**
 * version 3.0 upgrading for PHP 7.2
 * This is the base class for all objects
 * There are some functions which need to be overridden in the extending classes
 * @package default
 * @author arroWebs
 **/

class awBean {

  /**
   * Used in save function to handle add / update
   * @var boolean
   **/
  var $new_schema = false;

  /**
   * Table name
   * @var string
   **/
  var $table_name;

  /**
   * Table fields array Used to get tables column
   * overridden in child classes 
   * @var array
   **/
  var $column_fields;

  /**
   * Database connection object (ez-db object)
   *
   * @var ez-db object
   **/
  var $db;

  /**
   * DEPRECATED
   * @var string
   **/
  var $module_name;

  ##################################################

  
  function __construct() {
    global $db; // instance of ez-db object
    $this->db = $db;
  }


  /**
   * Returns class name of calling object
   *
   **/
  function get_summary() {

    return get_class($this);
  }


  /**
   * Following function returns one row from appropriate table based on supplied id
   * Returns Null if not found  & dies if database error.
   * It calls `fill_additional_fields_detail()` to add some more detail to the object. 
   *
   * @return Object of calling class
   **/
  function retrieve($id = -1) {

    $q = "select * from $this->table_name where id= '$id' ";
    
    # $rs is now an object
    $rs = $this->db->get_row($q)
       or $this->log_error("Retrieve operation failed ". $this->get_summary(). "($id) : ");

    foreach($this->column_fields as $field) {
       if(isset($rs->$field)) {
        $this->$field = $rs->$field;
      }
    }

    $this->fill_additional_fields_detail();

    return $this;
  }


  /**
   * This function is overridden by child class 
   * It will use to add additional fields detail to row.
   * @return 
   **/
  function fill_additional_fields_detail() {
    // Overridden
  }


  /**
   * Retrieves a row from the table based on value of field_name
   * Otherwise it will return null  
   * @return Object of calling class
   * @author 
   **/
  function retrieve_by_name($field_name = "name", $field_value) {
    
    $q = "select * from $this->table_name where $field_name='$field_value' and deleted = 0";
    
    $this->log->debug("retrieve_by_name Query is $q");
    
    $rs = $this->db->get_row($q)
      or $this->log_error("AWBean function `retrieve_by_name` failed " . $this->get_summary() . "( $field_name: $field_value): ");
      
    $this->log->info("retrieve_by_name() value of '$field_name' = ". $rs->$field_name);
    
    foreach($this->column_fields as $field) {
      
      if(isset($rs->$field)) {
        $this->$field = $rs->$field;
      
      }
    }
    
    $this->fill_additional_fields_detail();
    
    return $this;
  }


  /**
   * Following function updates record if `id` already exists else inserts 
   * It calls `save_relational_changes` to save changes to relational tables.
   * Generated ID for new object and assigns to it.
   * @return void
   **/
  function save() {
    global $current_user_id;
  
    $isupdate = true;
    
    if(!isset($this->id) || $this->id == "")  {
      $isupdate = false;
      $this->new_schema = true;
    }
  
    $this->date_modified = date('YmdHis');
    
    if(isset($current_user_id)) $this->modified_user_id = $current_user_id;
    
    if($isupdate) {
      
      $q = "update ";
    
    } else {
      
      $q = "insert into ";
      
      $this->date_created = date('YmdHis');
      
      if(isset($current_user_id)) $this->created_user_id = $current_user_id;
      
      if($this->new_schema) $this->id = create_guid();
    }
    
    $q .= $this->table_name . " set ";
    
    $first_pass = 0; 
    foreach($this->column_fields as $field) {
      
      if($isupdate && $field == "id") continue;
      
      if(isset($this->$field)) {
        
        if($first_pass == 0) $first_pass = 1;
        else $q .= ", ";
        
        $q .= $field . "='" .$this->db->escape($this->$field). "'";
      }
    }
    if($isupdate) {
      $q .= " where id='$this->id'";
      $this->log->debug("Update: $q");
      
    } else {
      $this->log->debug("Insert: $q");
    }
  
    # In success, db->query($q) returns #of rows affected. 
    # In failure, it returns false

    ($this->db->query($q) !== false)
      or $this->log_error("AWBean function `save` failed ");
    
    $this->save_relational_changes($isupdate);
  }


  /**
   * Following function is overridden by extending class i.e. child_class
   * It saves relational entries of record that saved by this function in relational tables. 
   * @return void
   **/
  function save_relational_changes($isupdate) {
    // Overridden Implementation
  }


  /**
   * Following function will delete the record logically means it will mark `deleted` as 1 
   * It will call another function `mark_relational_deleted` which will be overridden in extended class.
   * @return void
   **/
  function mark_deleted($id) {
    
    $q = "update $this->table_name set deleted = 1 where id = '$id'";

    # In success, db->query($q) returns #of rows affected. 
    # In failure, it returns false
    
    ($this->db->query($q) !== false)
      or $this->log_error("AWBean function `mark_deleted` failed ". $this->get_summary()." :");
      
    $this->mark_relational_deleted($id);
  }


  /**
   * This function will be overridden in extended class i.e. child_class 
   * It will be used to mark related records deleted.
   * @return void
   **/
  function mark_relational_deleted($id) {
    // Overridden in Child class
  }


  /**
   * Following function will delete the record physically.
   * It will call another function `relational_deleted` which will be overridden in extended class.
   * @return void
   **/
  function deleted($id) {
    $q = "delete from $this->table_name where id = '$id'";
    
    $this->db->query($q)
      or $this->log_error("AWBean function `deleted` failed  " . $this->get_summary() . ":"); 
      
    $this->relational_deleted($id);
  }


  /**
   * This function will be overridden in extended class i.e. child_class
   **/
  function relational_deleted($id) {
    // Overridden in Child class
  }


  /**
   * Get list rows of the table, includes all columns in the result
   *
   * PARAMS:
   * where (string) : Conditions to filter result
   * order_by (string) : field name + order (ASC /DESC ) ex. 'name ASC'
   * offset(integer) : for pagination | set -1(int) to bypass pagination and get all records
   *
   * @return Array
   *
   **/
  function get_list($where = "", $order_by = "", $offset = 0) {
  
    # the return object
    $response = array();
    
    # get the current record set
    $q = "select SQL_CALC_FOUND_ROWS * from $this->table_name ";

    if($where && $where != "") {
      $q .= " where ($where) and $this->table_name.deleted = 0";
    } else {
      $q .= " where $this->table_name.deleted = 0 ";
    }
    
    if($order_by && $order_by != "") {
      $q .= " order by $order_by";
    }

    # Added by PN: 2019-04-01 | To remove duplicate code as decided in the discussion with SR and SJ.  
    # This (get_list) function builds and executes the SQL and process result. 
    # get_list_by_query($q, $offset) function executes the SQL and process result. 
    # calling "get_list_by_query" to get SQL executed and get result in Response.
    $response = $this->get_list_by_query($q, $offset);
    return $response;   

  }



  /**
   * Appends related fields to each row of result from get list 
   * To be overridden in sub class to get related fields.
   * This function is called for each row in the get_list()
   **/
  function fill_additional_list_fields() {
   // Overridden 
  }


  /**
   * TODO: to fill all the form post or get variables to the variables.
   *
   **/
  function fill_from_request() {

  }
  

  /**
   * used in sub panels on Detail Views.
   * $q - query
   * $template - the Seed template
   * &$template - changed to direct object as reference object can not be passed by reference
   * @return Array
   **/
  function related_list($q, $template, &$offset, &$order_by, &$sort_order) {
    
    global $max_entries_per_panel;
    $response = Array();
    //exception handling
    
    $rs = $this->db->get_results($q);
     
    $this->log->debug("Finding linked records ". $this->get_summary().": ".$q);
    
    $response['row_count'] = $this->db->num_rows;
    
    $previous_offset = $offset - $max_entries_per_panel;
    $next_offset     = $offset + $max_entries_per_panel;
    
    
    $list = array();
    
    $class = get_class($template);
   
    if ($rs) { 
      
      $counter = 0;
      
      foreach($rs as $row) {
        if($counter >= $offset && ($counter-$offset) < $max_entries_per_panel) {
        
          $temp = new $class(); 
          $temp->retrieve($row->id);
          $list[] = $temp;
        }
        $counter ++;
      }   
    }
 
    $response['list'] = $list;
    $response['next_offset'] = $next_offset;
    $response['previous_offset'] = $previous_offset;
    $response['order_by'] = $order_by;
    $response['sort_order'] = $sort_order;
    
    return $response;
  }


  /**
   * Object to array for list fields
   *
   * @return array
   * @author 
   **/
  function get_list_view_data() {

    $return_array = Array();

    foreach($this->list_fields as $field) {

      // Added by PT : 2015-07-31 | to adjust Notices as Undefined property: stdClass
      if(isset($this->$field)) {
        $return_array[$field] = $this->$field;
        $this->log->debug("get_list_view_data() $field: ". $this->$field);
      }

    }

    return $return_array;
  }


  /**
   * Get result by a query, includes all columns in the result
   *
   * PARAMS:
   * q (string) : Query - DML(SELECT) statement
   * * set offset = -1 to get all the records / integer offset
   * @return Array
   *
   **/
  function get_list_by_query($q, $offset = 0) {
    global $max_entries_per_page;
  
    # Inject CALC ROW to $q and add $response['row_count'] | | SR/SJ Mar 2 2019   
    $q = (stripos($q, 'SQL_CALC_FOUND_ROWS') !== false) ? $q : preg_replace('/select/i', 'SELECT SQL_CALC_FOUND_ROWS', $q,1);

    # the return object
    $response = array();
    
    try {
      
      if($offset != -1) 
        $q .= " limit $offset, $max_entries_per_page";
      
      $this->log->info("get_list Query: $q");
      
      $rs = $this->db->get_results($q);

      $response['row_count'] = $this->db->get_var("SELECT FOUND_ROWS()");
      
      $list = array();
      
      $class = get_class($this);
      
      
      if($rs) {
        $row_counter = 0;
        
        foreach($rs as $row) {
  
          // to adjust $this for php5 | SR 8/2/2008 6:36:18 PM
          $temp = new $class();
          
          foreach($this->list_fields as $field) {

            // Added by PT : 2015-07-31 | to adjust Notices as Undefined property: stdClass
            if(isset($row->$field)) {
              $temp->$field = $row->$field;
            }

          }
          
          $temp->fill_additional_list_fields();
          
          $list[] = $temp;

        }
      }
    
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    

    $response['list'] = $list;
    
    if($offset != -1) {
      $previous_offset = $offset - $max_entries_per_page;
      $next_offset     = $offset + $max_entries_per_page;
      
      $response['next_offset']     = $next_offset;
      $response['previous_offset'] = $previous_offset;
    }

    return $response;   
  }


  /**
   * Log mysqli last error and display error on screen
   *
   **/
  function log_error($msg) {

    $this->log->fatal($msg.": ".print_r($this->db->captured_errors,1));
    die($msg.": ".$this->db->last_error);
  }


  /**
   * DEPRECATED (Use get_list() instead)
   * Function to get the list without pagination
   * set offset = -1 to get all the records / integer offset
   * @return array
   **/
  function get_list_on_site($where = "", $order_by = "", $offset = -1) {

    return $this->get_list($where , $order_by , $offset);
  }
  

  /**
   * DEPRECATED (Use get_list_by_query() instead)
   * Function to get the list without pagination for a query
   * @return array
   **/
  function get_list_by_query_on_site($q, $offset = -1) {

    return $this->get_list_by_query($q, $offset);
  }


  /**
   * DEPRECATED (Use get_list_by_query() instead)
   * Pagination with # of rows found
   * add by SJ for SQL_CALC_FOUND_ROWS
   * @return array
   **/
  function get_list_by_query_calc_row($q, $offset = 0) {

    return $this->get_list_by_query($q, $offset);
  }


  /**
   * DEPRECATED (Use get_list_by_query() instead)
   * Function to get the result with # of rows and without pagination for a query
   * @return array
   **/
  function get_list_by_query_on_site_calc_row($q,$offset = -1) {

    return $this->get_list_by_query($q, $offset);
  }


}
// END class 