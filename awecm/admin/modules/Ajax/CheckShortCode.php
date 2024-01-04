<?php

if(!defined('__URBAN_OFFICE__')) exit;

require_once('include/classes/Meta.php');
$focus = new Meta();

if(isset($_REQUEST['short_code'])) {

  #checking in the dom array
  $short_code = "{".$_REQUEST['short_code']."}";
  
  if(array_key_exists($short_code, $short_code_dom)) {

    $counter_ = 1;

    # Short code already exists.. checking in database for next available short code
    $counts = $focus->check_widget_short_code_counts($_REQUEST['short_code'], $_REQUEST['page_id']);
  
    echo json_encode([
      'status' => 'success',
      'short_code' => $_REQUEST['short_code']."_".($counts + $counter_)
    ]);

  }
  else {
    
    $counts = $focus->check_widget_short_code_counts($_REQUEST['short_code'], $_REQUEST['page_id']);

    $short_code = $counts == 0 ? $_REQUEST['short_code'] : $_REQUEST['short_code']."_".($counts+1);
    
    echo json_encode([
      'status' => 'success',
      'short_code' => $short_code
    ]);

  }

}
