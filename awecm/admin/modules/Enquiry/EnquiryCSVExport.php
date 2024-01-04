<?php if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

# Purpose: Export Enquiry CSV

$order_by   = empty($_REQUEST['order_by'])   ? "date_created" : $_REQUEST['order_by'];
$sort_order = empty($_REQUEST['sort_order']) ? "desc"         : $_REQUEST['sort_order'];

if(isset($_REQUEST['offset']) && $_REQUEST['offset'] != '') $offset = $_REQUEST['offset'];

if(isset($_REQUEST['p']) && $_REQUEST['p'] != '') $offset = ($_REQUEST['p'] - 1) * $max_entries_per_page;

if(!$offset) $offset = 0;

$name_email_val = isset($_REQUEST['name_email']) ? addslashes(trim($_REQUEST['name_email'])) : '';
$enquiry_type = isset($_REQUEST['enquiry_type']) ? addslashes(trim($_REQUEST['enquiry_type'])) : '';

$where = [];

if(!empty($_REQUEST['fromdate']) && !empty($_REQUEST['todate'])) {

  $fromdate = $_REQUEST['fromdate'];
  $fromdate = $fromdate." 00:00:00";
  $todate   = $_REQUEST['todate'];
  $todate   = $todate." 23:59:59";

  $where[]  = "date_created BETWEEN '$fromdate' AND '$todate'";

} elseif(!empty($_REQUEST['fromdate']) && empty($_REQUEST['todate'])) {

  $fromdate     = $_REQUEST['fromdate'];
  $fromdate     = $fromdate." 00:00:00";
  $current_date = date("Y-m-d"); 
  $current_date = $current_date." 23-59-59";

  $where[] = "date_created BETWEEN '$fromdate' AND '$current_date'";

} elseif(empty($_REQUEST['fromdate']) && !empty($_REQUEST['todate'])) {

  $todate  = $_REQUEST['todate']." 23:59:59";
  $where[] = "date_created <= '$todate'";

}

if(!empty($name_email_val)) {
  $where[] = " (CONCAT(first_name,' ',last_name) LIKE '%$name_email_val%' OR email LIKE '%$name_email_val%')";
}

if(!empty($enquiry_type)) {
  $where[] = " tag LIKE '%$enquiry_type%'";
}

$where[]      = "deleted = 0";
$where_clause = build_where_clause($where);
$time_stamp   = date("Ymd-h-i-s");
$file_name    = "Inquiry-".$time_stamp.".csv";

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment;filename={$file_name}");
$fh = fopen("php://output", 'w');

if($fh) {

  $assign_data = array();
  $row_header  = array('Submitted Date','Name','Email','Phone','Property Name','Move In Date', 'No Of People', 'Message','IP','User Agent');
  fputcsv($fh, $row_header);

  #Getting the enquiry list
  $sql = "SELECT CONCAT(first_name,' ',last_name) AS name, email, message, phone, date_created, user_detail, property_id, move_in_date, no_of_people
          FROM enquiry";

  if($where_clause) $sql .= " WHERE $where_clause";

  if($order_by && $order_by != "") $sql .= " ORDER BY $order_by";

  if($sort_order && $sort_order != "") $sql .= " $sort_order";

  $rs = $db->get_results($sql, ARRAY_A);
  if($rs) {

    foreach ($rs as $row) {

      $user_detail  = json_decode($row['user_detail']);
      
      if($row['move_in_date'] != '0000-00-00 00:00:00') {
        $row['move_in_date'] = date('m/d/Y',strtotime($row['move_in_date']));
      } else $row['move_in_date'] =  '';
      
      $row['no_of_people'] = !empty($row['no_of_people']) ? $row['no_of_people'] : '';
      
      if(!empty($row['property_id'])) {
        
        $qry = "SELECT title
                FROM page
                WHERE id = '".$row['property_id']."'
                 AND post_type = 'property'
                 AND status = 'published'
                 AND deleted = 0
                LIMIT 1";
        $property_name = $db->get_var($qry);
      } else {
        $property_name = '';
      }

      $assign_data['date_created'] = display_date_format($row['date_created']);
      $assign_data['name']         = trim($row['name']);
      $assign_data['email']        = $row['email'];
      $assign_data['phone']        = $row['phone'];
      $assign_data['message']      = wordwrap($row['message'],50);
      $assign_data['property']     = wordwrap($property_name,50);
      $assign_data['move_in_date'] = $row['move_in_date'];
      $assign_data['no_of_people'] = $row['no_of_people'];
      $assign_data['ip']           = $user_detail->ip;
      $assign_data['user_agent']   = wordwrap($user_detail->browser,50);

      fputcsv($fh, $assign_data);
    }
 }
 fclose($fh);
}

exit;
