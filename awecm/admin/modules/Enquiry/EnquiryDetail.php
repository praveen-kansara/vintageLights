<?php

if( !defined('__URBAN_OFFICE__') ) exit;

# Purpose: Enquiry Detail
require_once("include/classes/SystemUser.php");
$obj_user = new SystemUser();

require_once("include/classes/Customer.php");
$obj_customer = new Customer();

require_once("include/classes/Product.php");
$obj_prod = new Product();

require_once("include/classes/ProductCategory.php");
$obj_prod_cat = new ProductCategory();

require_once("include/classes/Enquiry.php");
$obj_enq = new Enquiry();


global $media_path_s3, $media_path_site, $s3_base_path, $bucket_name;
$xtpl = new XTemplate("modules/Enquiry/EnquiryDetail.html");

# Set order by
$order_by   = empty($_REQUEST['order_by'])   ? "date_created" : $_REQUEST['order_by'];
$sort_order = empty($_REQUEST['sort_order']) ? "desc"         : $_REQUEST['sort_order'];

# Set page and offset
if(isset($_REQUEST['offset']) && $_REQUEST['offset'] != '') $offset = $_REQUEST['offset'];
if(isset($_REQUEST['p']) && $_REQUEST['p'] != '') $offset = ($_REQUEST['p'] - 1) * $max_entries_per_page;

if(!$offset) $offset = 0;
$user_data = array();

# Create Where
$where = [];
$customer_id = $email = $status = "";
$where[] = "deleted = 0";

# Display Success message
if(!empty($_SESSION['status'])) {
  $xtpl->assign('SUCCESS_MESSAGE',$msg_dom['eCatalog']);
  $xtpl->parse('main.msg');
  unset($_SESSION['status']);
}


if(!empty($_REQUEST['email'])) {
  $email = trim($_REQUEST['email']);
  $where[] = "email ='".$db->escape($email)."' ";
  $xtpl->assign("USER_EMAIL", $_REQUEST['email']);
}

# Get all type of DOM
$enq_data = $obj_enq->get_enq_data($where, 1);

$cust_id = '';
# Get User Name
if(!empty($enq_data)) {
  $user_data[0] = '';
  $user_data = array_column($enq_data, 'name');

  $xtpl->assign('USER_NAME', $user_data[0]);
}

if(!empty($enq_data)) {
  $user_data[0] = '';
  $user_data = array_column($enq_data, 'name');

  $xtpl->assign('USER_NAME', $user_data[0]);
}

$xtpl->assign('NAVIGATION' ,$navigation);

$xtpl->parse('main');
$xtpl->out('main');
