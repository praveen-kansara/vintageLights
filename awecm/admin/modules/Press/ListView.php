<?php
if(!defined('__URBAN_OFFICE__')) exit;

$xtpl= new XTemplate("modules/Press/ListView.html");

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/Meta.php");
$meta = new Meta();

require_once("include/classes/SystemUser.php");
$system_user = new SystemUser();


# Filters in query parameters
$order_by   = empty($_REQUEST['order_by'])   ? "date_modified" : $_REQUEST['order_by'];
$sort_order = empty($_REQUEST['sort_order']) ? "desc"          : $_REQUEST['sort_order'];

if(isset($_REQUEST['offset']) && $_REQUEST['offset'] != '') $offset = $_REQUEST['offset'];
if(isset($_REQUEST['p']) && $_REQUEST['p'] != '') $offset = ($_REQUEST['p'] - 1) * $max_entries_per_page;

if(!$offset)     $offset     = 0;

$where[] = "deleted = 0";
$where[] = "post_type = 'press'";

$name = $id = $status = "";

if(!empty($_REQUEST['id']))        {  $id     = trim($_REQUEST['id']); }
if(!empty($_REQUEST['name']))      {  $name   = trim($_REQUEST['name']);      $where[] = "title LIKE '%".$db->escape($name)."%' "; }
if(!empty($_REQUEST['status']))    {  $status = trim($_REQUEST['status']);    $where[] = "status = '".$db->escape($status)."'"; }
if(!empty($_REQUEST['search_post_type'])) {  $search_post_type = trim($_REQUEST['search_post_type']); $where[] = "post_type = '".$db->escape($search_post_type)."'"; }

$where_clause = count($where) > 0 ? build_where_clause($where) : '';
$query        = $focus->generate_list_query($where_clause, $order_by.' '.$sort_order, $offset);

$response     = $focus->get_list_by_query($query, $offset);
$row_count    = $response['row_count'];
$navigation   = _build_navigation($row_count, $offset, 5);

if($response['list']) {

  $r_count = 0;
  foreach($response['list'] as $seed) {

    $row = $seed->get_list_view_data();

    $page_url = '';
    if($row['status'] == 'published') {
      $page_url = $row['uri'];
      $xtpl->assign('PAGE_URL', $page_url);
      $xtpl->parse('main.pagerow.page_view_btn');
    }

    $row['date_modified']    = display_date_format($row['date_modified']);
    $row['STATUS_DOM']       = select_list($content_status_dom, $row['status'], 1);
    $row['status']		       = ucfirst($row['status']);

    $page_meta_array         =  $meta->get_all_page_meta($row['id']);
    $row['META_TITLE']       = isset($page_meta_array['meta_title']) ? $page_meta_array['meta_title'] : "";
    $row['META_DESCRIPTION'] = isset($page_meta_array['meta_description']) ? $page_meta_array['meta_description'] : "";
    $row['STATUS']           = select_list($content_status_dom, $row['status'], 1);

  	$system_user->retrieve($row['created_user_id']);
    $row['created_by'] = $system_user->name;

    if($r_count%2 == 0){
      $row_cls = 'row-even';
    } else $row_cls = 'row-odd';

    $row['row_cls'] = $row_cls;
    $xtpl->assign('row', $row);
    $xtpl->parse('main.pagerow');

    $r_count++;

  }

} else {
  $xtpl->parse("main.norow");
}

$xtpl->assign("ORDER_BY",   $order_by);
$xtpl->assign("SORT_ORDER", $sort_order);
$xtpl->assign("OFFSET",     $offset);

$xtpl->assign("NAME_ORDER_BY",       make_order_by("title",         'Title'));
$xtpl->assign("SEQUENCE_ORDER_BY",   make_order_by("(sequence*1)",   'Sequence'));
$xtpl->assign("STATUS_ORDER_BY",     make_order_by("status",        'Status'));
$xtpl->assign("CREATED_BY_ORDER_BY", make_order_by("created_by",    'Author'));
$xtpl->assign("MODIFIED_ORDER_BY",   make_order_by("date_modified", 'Modified On'));

require_once("modules/Press/Search.php");
$xtpl->assign('SEARCH_VIEW', $searchView);
$xtpl->assign('NAVIGATION',  $navigation);
$xtpl->parse('main');
$xtpl->out('main');
