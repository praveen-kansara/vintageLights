<?php

require_once("include/classes/SystemUser.php");

$focus = new SystemUser();
$xtpl  = new XTemplate("modules/SystemUser/ListView.html");

# Filters in query parameters
$order_by = empty($_REQUEST['order_by']) ? "date_modified" : $_REQUEST['order_by'];
$sort_order = empty($_REQUEST['sort_order']) ? "desc" : $_REQUEST['sort_order'];
if(isset($_REQUEST['offset']) && $_REQUEST['offset'] != '') $offset = $_REQUEST['offset'];
if(isset($_REQUEST['p']) && $_REQUEST['p'] != '') $offset = ($_REQUEST['p'] - 1) * $max_entries_per_page;

if(!$offset) $offset = 0;

$where  = [];
$name = $email = $is_active = "";
$where[] = "deleted = 0";

if(!empty($_REQUEST['name'])) { $name = trim($_REQUEST['name']);    $where[] = "name LIKE '%".$db->escape($name)."%' "; }
if(!empty($_REQUEST['email'])) { $email = trim($_REQUEST['email']); $where[] = "email LIKE '%".$db->escape($email)."%' ";}
if(isset($_REQUEST['is_active']) && $_REQUEST['is_active'] != '') $is_active = $_REQUEST['is_active'];
if($is_active && $is_active != '') {
  if ($is_active == 'active')  $where[] = "is_active = 1";
  if ($is_active == 'inactive') $where[] = "is_active = 0";
}

$where_clause = build_where_clause($where);
$response = $focus->get_list($where_clause, $order_by.' '.$sort_order, $offset);
$row_count = $response['row_count'];
$navigation = _build_navigation($row_count, $offset, 4);


if($response['list']) {

  $r_count = 0;

  foreach($response['list'] as $seed) {

    $row = $seed->get_list_view_data();
    $row['class'] = $row['is_active'] == 1 ? "success" : "default";
    $row['text']  = $row['is_active'] == 1 ? "Active" : "Inactive";
    $row['date_modified']  = display_date_format($row['date_modified']);
    
    if($r_count%2 == 0){
      $row_cls = 'row-even';
    } else $row_cls = 'row-odd';

    $row['row_cls'] = $row_cls;

    $xtpl->assign('row',$row);

    $xtpl->parse('main.systemusersrow');
    $r_count++;
    
  }
} else {
  $xtpl->parse("main.norow");
}

$xtpl->assign("NAME_ORDER_BY",    make_order_by("name",  "Name"));
$xtpl->assign("EMAIL_ORDER_BY",   make_order_by("email", "Email"));
$xtpl->assign("MODIFIED_ORDER_BY", make_order_by("date_modified", "Modified On"));

require_once("modules/SystemUser/Search.php");
$xtpl->assign('SEARCH_VIEW',$searchView);
$xtpl->assign('NAVIGATION',$navigation);
$xtpl->parse('main');
$xtpl->out('main');
