<?php

if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

/* Index File Variables */

$main_content = null;

/* Common Variables used in system */
$order_by       = null;
$sort_order     = null;
$offset         = null;
$total_pages    = null;
$email_password = false;
$new_password   = null;
$mime_type      = null;
$old_orig_image = null;
$where      = array();

switch($module) {

    case 'Ajax':
    /* Ajax Stub Variables */
    $name = null;
    $student_array = $results = array();
    break;

    case 'SystemUser':
    /* Ajax Stub Variables */
    $checked_status = null;
    break;

    case 'Page':
    $search_post_type = $meta_keyword = $meta_description = $status = $domvalue =  null;
    break;

    case 'Product':
    $meta_keyword = $meta_description = $product_category = $domvalue = null;
    break;
    
    case 'Category':
    $meta_keyword = $meta_description = $domvalue = null;
    break;

    case 'Enquiry':
    $fromdate = $todate = $days =null;
    break;

    case 'Slider':
    $status = $domvalue =  $caption = null;
    break;

    case 'Redirection':
    $type = $uri = null;
    break;

}
