<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/Redir.php");
$focus = new Redir();

require_once("include/classes/SystemUser.php");
$SystemUserObj = new SystemUser();

$xtpl = new XTemplate("modules/Redirection/ListView.html");

$max_entries_per_page = 50;

# Filters in query parameters
$order_by   = empty($_REQUEST['order_by'])   ? "date_modified" : $_REQUEST['order_by'];
$sort_order = empty($_REQUEST['sort_order']) ? "desc"         : $_REQUEST['sort_order'];
if(isset($_REQUEST['offset']) && $_REQUEST['offset'] != '') $offset = $_REQUEST['offset'];
if(isset($_REQUEST['p']) && $_REQUEST['p'] != '')           $offset = ($_REQUEST['p'] - 1) * $max_entries_per_page;
if(!$offset)     $offset     = 0;

$name  = $status = "";
$where[] = "deleted = 0";


if(!empty($_REQUEST['id']))     { $id =     trim($_REQUEST['id']); }
if(!empty($_REQUEST['uri']))    { $uri =   trim($_REQUEST['uri']);   $where[]  = "uri LIKE '%".$db->escape($uri)."%' "; }


$where_clause = build_where_clause($where);
$response = $focus->get_list($where_clause, $order_by.' '.$sort_order, $offset);
$row_count = $response['row_count'];
$navigation = _build_navigation($row_count, $offset, 4);

if($response['list']) {
  $r_count = 0;

  foreach($response['list'] as $seed) {


    $row = $seed->get_list_view_data();
    $row['date_modified']  = display_date_format($row['date_modified']);  

    if(!empty($row['modified_user_id'])) {
      $row['created_by']     = $SystemUserObj->retrieve($row['modified_user_id'])->name;
    }

    if($r_count%2 == 0) $row_cls = 'row-even';
    else $row_cls = 'row-odd';

    $row['row_cls'] =$row_cls;

    $xtpl->assign('row', $row);
    $xtpl->parse('main.pagerow');

    $r_count++;

  }

} else {
  $xtpl->parse('main.no_records');
}

$xtpl->assign("ORDER_BY",   $order_by);
$xtpl->assign("SORT_ORDER", $sort_order);
$xtpl->assign("OFFSET",     $offset);

$xtpl->assign("MODIFIED_ORDER_BY", make_order_by("date_modified", 'Modified On'));

require_once("modules/Redirection/Search.php");
$xtpl->assign('SEARCH_VIEW', $searchView);

$xtpl->assign('NAVIGATION', $navigation);
$xtpl->parse('main');
$xtpl->out('main');
