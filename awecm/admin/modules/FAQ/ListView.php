<?php
if( !defined('__URBAN_OFFICE__') ) exit;

$xtpl = new XTemplate("modules/FAQ/ListView.html");

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/Meta.php");
$page_meta_obj = new Meta();

require_once("include/classes/SystemUser.php");
$system_user = new SystemUser();

# Filters in query parameters
$order_by   = empty($_REQUEST['order_by'])   ? "date_modified" : $_REQUEST['order_by'];
$sort_order = empty($_REQUEST['sort_order']) ? "desc"           : $_REQUEST['sort_order'];

if(isset($_REQUEST['offset']) && $_REQUEST['offset'] != '') $offset = $_REQUEST['offset'];
if(isset($_REQUEST['p']) && $_REQUEST['p'] != '')           $offset = ($_REQUEST['p'] - 1) * $max_entries_per_page;
if(!$offset) $offset = 0;

$question = $id = $status = "";
$where[] = "deleted = 0";
$where[] = "post_type = 'faq'";

if(!empty($_REQUEST['question']))   {$question   = trim($_REQUEST['question']);   $where[] = "title LIKE '%".$db->escape($question)."%' ";}
if(!empty($_REQUEST['status'])) {$status = trim($_REQUEST['status']); $where[] = "status = '".$db->escape($status)."'";}
if(!empty($_REQUEST['id']))     {$id = trim($_REQUEST['id']); }

$where_clause = count($where) > 0 ? build_where_clause($where) : '';
$query        = $focus->generate_list_query($where_clause, $order_by.' '.$sort_order, $offset); 

$response     = $focus->get_list_by_query($query, $offset);
$row_count    = $response['row_count'];
$navigation   = _build_navigation($row_count, $offset, 5);

if($response['list']) {

    $r_count = 0;

  foreach($response['list'] as $seed) {

    $row = $seed->get_list_view_data();

    $system_user->retrieve($row['created_user_id']);
    $row['created_by'] = $system_user->name;

    $row['date_modified']    = display_date_format($row['date_modified']);
    $row['STATUS_DOM']       = select_list($content_status_dom, $row['status'], 1);
    $row['status']           = ucfirst($row['status']);

    # Check for meta data 
    $page_meta_array         =  $page_meta_obj->get_all_page_meta($row['id']);
    $row['META_TITLE']       = isset($page_meta_array['meta_title']) ? $page_meta_array['meta_title'] : "";
    $row['META_DESCRIPTION'] = isset($page_meta_array['meta_description']) ? $page_meta_array['meta_description'] : "";

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

$xtpl->assign("QUESTION_ORDER_BY",       make_order_by("title",         'Question'));
$xtpl->assign("SEQUENCE_ORDER_BY",   make_order_by("(sequence*1)",      'Sequence'));
$xtpl->assign("STATUS_ORDER_BY",     make_order_by("status",        'Status'));
$xtpl->assign("MODIFIED_ORDER_BY",   make_order_by("date_modified", 'Modified On'));
$xtpl->assign("CREATED_BY_ORDER_BY", make_order_by("created_by",    'Author'));

$categories = $focus->get_pages_by_post_type('category');

require_once("modules/FAQ/Search.php");
$xtpl->assign('SEARCH_VIEW', $searchView);


$xtpl->assign('NAVIGATION', $navigation);
$xtpl->parse('main');
$xtpl->out('main');
