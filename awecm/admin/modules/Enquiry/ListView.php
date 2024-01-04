<?php
if( !defined('__URBAN_OFFICE__') ) exit;

# Purpose: Enquiry Listview
$max_entries_per_page = 100;
require_once("include/classes/Enquiry.php");
$focus = new Enquiry();

require_once("include/classes/Page.php");
$obj_page = new Page();

$xtpl  = new XTemplate("modules/Enquiry/ListView.html");

# Filters in query parameters
$order_by   = empty($_REQUEST['order_by'])   ? "date_created" : $_REQUEST['order_by'];
$sort_order = empty($_REQUEST['sort_order']) ? "desc"         : $_REQUEST['sort_order'];

if(isset($_REQUEST['offset']) && $_REQUEST['offset'] != '') $offset = $_REQUEST['offset'];
if(isset($_REQUEST['p']) && $_REQUEST['p'] != '') $offset = ($_REQUEST['p'] - 1) * $max_entries_per_page;

if(!$offset) $offset = 0;

$name_email_val = isset($_REQUEST['name_email']) ? addslashes(trim($_REQUEST['name_email'])) : '';
$enquiry_type = isset($_REQUEST['enquiry_type']) ? addslashes(trim($_REQUEST['enquiry_type'])) : '';

$where  = [];

# Date filter
if(!empty($_REQUEST['fromdate']) && !empty($_REQUEST['todate'])) {

  $fromdate = $_REQUEST['fromdate'];
  $fromdate = $fromdate." 00:00:00";
  $todate   = $_REQUEST['todate'];
  $todate   = $todate." 23:59:59";

  $where[]  = "date_created BETWEEN '$fromdate' AND '$todate'";

} elseif(!empty($_REQUEST['fromdate']) && empty($_REQUEST['todate'])) {

  $fromdate = $_REQUEST['fromdate'];
  $fromdate = $fromdate." 00:00:00";
  $current_date = date("Y-m-d"); 
  $current_date = $current_date." 23-59-59";

  $where[]  = "date_created BETWEEN '$fromdate' AND '$current_date'";

} elseif(empty($_REQUEST['fromdate']) && !empty($_REQUEST['todate'])) {

  $todate = $_REQUEST['todate']." 23:59:59";
  $where[]  = "date_created <= '$todate'";

} 

if(!empty($name_email_val)) {
  $where[] = " (CONCAT(first_name,' ',last_name) LIKE '%$name_email_val%' OR email LIKE '%$name_email_val%')";
}

if(!empty($enquiry_type)) {
  $where[] = " tag LIKE '%$enquiry_type%'";
}

$where[]      = "deleted = 0";
$where_clause = build_where_clause($where);
$response     = $focus->get_list($where_clause, $order_by.' '.$sort_order, $offset);
$row_count    = $response['row_count'];
$navigation   = _build_navigation($row_count, $offset, 4);

if($response['list']) {

  $r_count = 0;
  foreach($response['list'] as $seed) {

    $row = $seed->get_list_view_data();

    $row['name'] = $row['first_name'].' '.$row['last_name'];

    $row['date_created']  = display_date_format($row['date_created']);
    $row['short_msg']     = substr($row['message'],0,30);

    if(strlen($row['message']) > 30) 
      $row['short_msg']  =  substr($row['message'],0,30)."...";

    $user_detail = json_decode($row['user_detail']);

    $row['ip'] = $row['user_agent'] = '';

    if(!empty($user_detail) && !empty($user_detail->ip)) {
      $row['ip']          = $user_detail->ip;
      $row['user_agent']  = $user_detail->browser;
    }

    if($row['move_in_date'] != '0000-00-00 00:00:00') {
      $row['move_in_date'] = date('m/d/Y',strtotime($row['move_in_date']));
    } else $row['move_in_date'] =  '';

    $row['tag'] = $enquiry_dom[$row['tag']];

    if(!empty($row['property_id'])) {

      $where_array = array(
        "id = '".$row['property_id']."'",
        "post_type = 'property'",
      );

      $property_detail = $obj_page->get_page_by_condition($where_array);
      $row['property_name'] = $property_detail->title;
      $row['property_edit_link'] = './?module=Property&action=EditView&id='.$row['property_id'];
    } else {
      $row['property_name'] = $row['property_edit_link'] = "";
    }

    if($r_count%2 == 0){
      $row_cls = 'row-even';
    } else $row_cls = 'row-odd';

    $row['row_cls']   = $row_cls;
    $row['row_count'] = $r_count;

    $xtpl->assign('row',$row);

    if(strlen($row['message']) > 30) {
      $xtpl->parse('main.enquiryrow.read_more_link');
    }

    $xtpl->parse('main.enquiryrow');
    $r_count++;
  }

} else {
  $xtpl->parse('main.no_records');
}
$xtpl->assign("ORDER_BY",   $order_by);
$xtpl->assign("SORT_ORDER", $sort_order);
$xtpl->assign("OFFSET",     $offset);

$xtpl->assign("NAME_ORDER_BY",    make_order_by("CONCAT(first_name,' ',last_name)",  "Name"));
$xtpl->assign("EMAIL_ORDER_BY",   make_order_by("email", "Email"));
$xtpl->assign("ADDEDON_ORDER_BY", make_order_by("date_created", "Date"));

require_once("modules/Enquiry/Search.php");
$xtpl->assign('SEARCH_VIEW',$searchView);

$xtpl->assign('NAVIGATION',$navigation);
$xtpl->parse('main');
$xtpl->out('main');
