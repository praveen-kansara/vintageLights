<?php
if(!defined('__URBAN_OFFICE__')) exit;

# to save the search done by the user

function save_search() {

  global $module, $action;
  global $db;
  global $current_user_id;

  $user_id = $current_user_id;
  if(!$user_id) $user_id = $_SERVER['PHP_AUTH_USER'];
  if(!$user_id) $user_id = 'admin';

  if( count($_GET) <= 2 ) {

    // retrieve and push to $_GET
    $q = "select search_params from search_save where module = '$module' and user_id = '$user_id'";
    $search_params= unserialize($db->get_var($q));

    if($search_params){
      foreach($search_params as $field_name => $field_value )  {
         $_REQUEST[$field_name] = $field_value;
      }
    }
  } else {
    $q = "select count(*) cnt from search_save where module = '$module' and user_id = '$user_id'";
    $cnt = $db->get_var($q);

    if($cnt) $q = "update search_save set search_params = '".serialize($_GET)."', date_modified = now() where module='$module' and user_id = '$user_id'";
    else $q = "insert into search_save set id='".create_guid()."', module = '$module', search_params = '".serialize($_GET)."', user_id='$user_id', date_created = now(), date_modified = now()";
    $db->query($q);
  }
}

function build_where_clause($where) {
  $where_clause = "";
  foreach($where as $foo) $where_clause .= " ". ( ($where_clause) ? "and" : "" ) ." $foo";
  $where_clause = trim($where_clause);
  if(!$where_clause) $where_clause = "1";
  return $where_clause;
}

function build_where_clause_with_or($where) {
  $where_clause = "";
  foreach($where as $foo) $where_clause .= " ". ( ($where_clause) ? "or" : "" ) ." $foo";
  $where_clause = trim($where_clause);
  if(!$where_clause) $where_clause = "1";
  return $where_clause;
}

function redirect( $url) {
  /*
   * If the headers have been sent, then we cannot send an additional location header
   * so we will output a javascript redirect statement.
   */
  if (headers_sent()) {
    echo "<script>document.location.href='$url';</script>\n";
  } else {
    header( "Location: ". $url );
  }
  exit();
}

/**
*   returns list for select dropdown list
*/
function select_list($row, $selected="", $blank_row=0, $txt = 'Select') {
  if($blank_row) $ret = "<option value=\"\">$txt</option>";
  foreach($row as $k => $v) $ret .= "<option value=\"$k\"". ( ($k == $selected) ? " selected" : "" ) .">$v</option>";
  return $ret;
}

function select_list_with_id($row_list, $selected="", $blank_row=0, $text = "Select") {
  $ret = "";
  if($blank_row) 
  $ret .= "<option value=\"\">$text</option>";
  if($row_list) foreach($row_list as $row) $ret .= "<option value=\"". $row->id ."\"". ( ($row->id ==$selected) ? " selected" : "" ) .">". $row->name ."</option>";
  return $ret;
}

function multiple_select_list($row_list, $selected) {
  if($blank_row) $ret = "<option value=\"\" disabled>$text</option>";
  foreach($row_list as $k => $v) {
    $ret .= "<option value=\"$k\"";
    if(is_array($selected)) if(in_array($k, $selected)) $ret .= " selected";
    else if($k == $selected) $ret .= " selected";
    $ret .= ">". $v ."</option>";
  }
  return $ret;
}


function make_dd_options($row, $selected="", $blank_row=0) {
  if($blank_row) $ret = "<option value=\"\"></option>";
  foreach($row as $k => $v) {
    $v = stripslashes($v);
    $ret .= "<option value=\"".htmlspecialchars($v)."\"". ( ($v == $selected) ? " selected" : "" ) .">".$v."</option>";
  }
  return $ret;
}

function create_check_boxes($dom, $selected, $var, $tabindex=1) {
  foreach($dom as $k => $v) {
    $ret .= "<input type=\"checkbox\" class=\"checkbox\" tabindex=\"$tabindex\" name=\"". $var ."[". $k ."]\" ";
    if(strpos($selected, $k) !== false) $ret .= "checked";
    $ret .= "> $v<br />";
  }
  if($ret) $ret = substr($ret, 0, strlen($ret)-6);    // remove the last '<br />'
  return $ret;
}


/**
* Construct the link for order by clause
*
*/
function make_order_by($field_name, $field_label="") {
  global $order_by;
  global $sort_order;

  if(!$field_label) $field_label = $field_name;

  $ret = "<a href=\"javascript:doSort('$field_name',";

  if($field_name == $order_by) {
    if(strtolower($sort_order) == "asc") $ret .= "'desc');\" title=\"Sort descending on $field_label\">$field_label <i class='fa fa-fw fa-caret-up'></i>";
    else $ret .= "'asc');\" title=\"Sort ascending on $field_label\">$field_label <i class='fa fa-fw fa-caret-down'></i>";
  } else {
    $ret .= "'asc');\" title=\"Sort ascending on $field_label\">$field_label";
  }

  $ret .= "</a>";

  return $ret;

}


function create_guid() {
  $microTime = microtime();
  list($a_dec, $a_sec) = explode(" ", $microTime);

  $dec_hex = sprintf("%x", $a_dec* 1000000);
  $sec_hex = sprintf("%x", $a_sec);

  ensure_length($dec_hex, 5);
  ensure_length($sec_hex, 6);

  $guid = "";
  $guid .= $dec_hex;
  $guid .= create_guid_section(3);
  $guid .= '-';
  $guid .= create_guid_section(4);
  $guid .= '-';
  $guid .= create_guid_section(4);
  $guid .= '-';
  $guid .= create_guid_section(4);
  $guid .= '-';
  $guid .= $sec_hex;
  $guid .= create_guid_section(6);

  return $guid;

}

function create_guid_section($characters) {
  $return = "";
  for($i=0; $i<$characters; $i++)
  {
    $return .= sprintf("%x", rand(0,15));
  }
  return $return;
}

function ensure_length(&$string, $length) {
  $strlen = strlen($string);
  if($strlen < $length)
  {
    $string = str_pad($string,$length,"0");
  }
  else if($strlen > $length)
  {
    $string = substr($string, 0, $length);
  }
}


/**
*   Logger
*   updated: 3/8/2007 5:23PM SR
*/
function xlog($mode, $summary="", $message="") {

  global $db;
  $sitename = 'site';
  $handle = fopen($sitename . ".log", "a");
  $s = "[".date("m-d-Y h:i:s a")."] [$mode]\n$summary\n$message\n";
  if ($db->last_error) $s.= $db->last_error . "\n";
  fwrite($handle, $s);
  fclose($handle);
}




// panel specific functions
/*
function panel_heading($title, $panel_id, $toggle_id) {
  $the_html = <<<EOQ
  <br /><table cellpadding="2" cellspacing="0" border="0" width="100%"><tr><td><h3>$title</h3></td><td align="right"><div id="$toggle_id">
  <a href="javascript:toggleLayer('$panel_id', '$toggle_id', 'none');"><img src="images/hide.gif" border="0"> Hide</a></div></td></tr></table>
  <div id="$panel_id">
EOQ;

  return $the_html;

}
*/

// panel specificaly  for EventsCRM

function panel_heading($title, $panel_id, $toggle_id) {
  $the_html = <<<EOQ
  <br />
  <h3>$title</h3>
  <div id="$panel_id">
EOQ;
  return $the_html;
}

function panel_navigation($panel_id, $id, $entries, $offset, $next_offset, $previous_offset, $order_by, $sort_order) {
  global $module;
  global $max_entries_per_panel;

  $log_string = "Entries: $entries | Current Offset: $offset |";

  $start = "<b>&laquo;</b>Start";
  $prev  = "<b>&#8249;</b>Prev ";
  $next  = " Next<b>&#8250;</b>";
  $last  = " Last<b>&raquo;</b>";

  $nav = "<table cellpadding=3 cellspacing=0 border=0 class=\"nav fine\" align=\"right\"><tr><td class=\"inactive\">$start</td><td class=\"inactive\">$prev</td><td>0 - 0 of 0</td><td class=\"inactive\">$next</td><td class=\"inactive\">$last</td></tr></table>";

  if($entries) {
    $nav = "<table cellpadding=2 cellspacing=0 border=0 class=\"nav fine\" align=\"right\"><tr>";

    // Start Page
    $nav .= "<td class=\"inactive\">";
    if($offset) $nav .= "<a class=\"page\" href=\"". make_panel_url($panel_id, $id, $order_by, $sort_order, 0). "\" title=\"Show Beginning\">$start</a>";
    else $nav .= $start;
    $nav .= "</td>";


    // Previous Page
    if($previous_offset <= 0) $previous_offset = 0;
    $log_string .= "Previous Offset: $set_offset | ";


    $nav .= "<td class=\"inactive\">";
    if($offset && $previous_offset >= 0) $nav .= "<a class=\"page\" href=\"". make_panel_url($panel_id, $id, $order_by, $sort_order, $previous_offset). "\" title=\"Show Previous\">$prev</a>";
    else $nav .= $prev;
    $nav .= "</td>";


    // the blurb
    $shown = min($offset + $max_entries_per_panel, $entries);
    $nav .= "<td class=\"nav\">". ($offset + 1) ." to ". $shown ." of ". $entries ."</td>";


    // Next Page
    if($next_offset >= $entries) $next_offset = 0;
    $log_string .= "Next Offset: $set_offset | ";

    $nav .= "<td class=\"inactive\">";
    if($next_offset) $nav .= "<a class=\"page\" href=\"". make_panel_url($panel_id, $id, $order_by, $sort_order, $next_offset). "\" title=\"Show Next\">$next</a>";
    else $nav .= $next;
    $nav .= "</td>";


    // Last Page
    $residue = ($entries % $max_entries_per_panel);
    if ($residue) $last_offset = $entries - $residue;
    else $last_offset = $entries - $max_entries_per_panel;

    if($last_offset == $offset) $last_offset = 0;    // recheck this on 10+

    $log_string .= "Last Offset: $set_offset";
    $nav .= "<td class=\"inactive\">";
    if($last_offset) $nav .= "<a class=\"page\" href=\"". make_panel_url($panel_id, $id, $order_by, $sort_order, $last_offset). "\" title=\"Show Last\">$last</a>";
    else $nav .= $last;
    $nav .= "</td>";

    $nav .= "</tr></table>";
  }

  return $nav;

} // end of function panel_navigation //



function make_panel_url($panel_id, $id, $order_by, $sort_order, $offset=0) {
  global $module;

  return "javascript:loadPanel('$panel_id', 'index.php?module=$module&action=LoadPanel&id=$id&panel_id=$panel_id&order_by=$order_by&sort_order=$sort_order&offset=$offset');";

}


function make_panel_order_by($panel_id, $id, $field_label, $field_name, $order_by, $sort_order) {


  $img = "";
  $title = "Sort Ascending on $field_label";
  $set_order = "asc";
  if($field_name == $order_by) {
    $set_order = ($sort_order == "desc") ? "asc" : "desc";

    $img = ($sort_order == "desc") ? "arrow_down.png" : "arrow_up.png";

    $title = "Sort " . (($set_order == "asc") ? "Ascending" : "Descending") . " on $field_label";

    $img_path = "<img src=\"images/$img\" align=\"absmiddle\" border=\"0\">";

    return "<a href=\"". make_panel_url($panel_id, $id, $field_name, $set_order). "\" title=\"$title\">$field_label $img_path</a>";

  } else {

    return "<a href=\"". make_panel_url($panel_id, $id, $field_name, $set_order). "\" title=\"$title\">$field_label</a>";
  }


}


function create_shortcut_header($title="Shortcuts") {
  $ret = <<<EOQ
<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
<td style="background:url(images/scL.png) no-repeat top left;" width="5"><img src="images/blank.gif" width="5" height="21" border="0"></td>
<td style="background:url(images/scB.png) repeat-x;font: bold 12px Arial;padding:0px 3px;" width="100%">$title</td>
<td style="background:url(images/scR.png) no-repeat top right;" width="9"><img src="images/blank.gif" width="9" height="21" border="0"></td>
</tr></table>
EOQ;

  return $ret;

}



function prettify_image_name($image_name) {

  $image_name = strtolower(str_replace("/","", $image_name));
  $image_name = str_replace("(","", $image_name);
  $image_name = str_replace(")","", $image_name);
  $image_name = str_replace("_","-", $image_name);
  $image_name = str_replace(" ","-", $image_name);

  return $image_name;

}

function make_image_name($id, $extn, $prefix="orig") {
  $file_name = $prefix."_".$id.$extn;
  return $file_name;
}

function make_thumbnail_image($max_width=103, $max_height=82, $extn, $orig_img, $thumb_image, $imgPath) {

    $main_img_file = $imgPath.$orig_img;
    $img_thumb_file = $imgPath.$thumb_image;

    switch($extn) {
        case ".gif" : $mainImage = imagecreatefromgif($main_img_file) or die('Problem In opening Source Image');
                                  break;

        case ".jpg" : $mainImage = imagecreatefromjpeg($main_img_file) or die('Problem In opening Source Image');
                      break;
        case ".jpeg" : $mainImage = imagecreatefromjpeg($main_img_file) or die('Problem In opening Source Image');
                      break;
        case ".png" : $mainImage = imagecreatefrompng($main_img_file) or die('Problem In opening Source Image');
                      break;
    }

    $mainWidth = imagesx($mainImage);
    $mainHeight = imagesy($mainImage);

    if($mainWidth > $max_width || $mainHeight > $max_height) {

      if($mainWidth > $mainHeight) $factor = $max_width / $mainWidth;
      else $factor = $max_height / $mainHeight;

      $thumbWidth = $mainWidth * $factor;    $thumbWidth  = round($thumbWidth,0);
      $thumbHeight = $mainHeight * $factor; $thumbHeight = round($thumbHeight,0);

    } else {
        $thumbWidth = $mainWidth;
        $thumbHeight = $mainHeight;
    }

    $myThumbnail = imagecreatetruecolor($max_width, $max_height);

    $bgColor = imagecolorallocate($myThumbnail, 230, 230, 230);

    imagefill($myThumbnail, 0, 0, $bgColor);

    $destX = ($max_width - $thumbWidth) / 2;
    $destY = ($max_height - $thumbHeight) / 2;

    imagecopyresampled($myThumbnail, $mainImage, $destX, $destY, 0, 0, $thumbWidth, $thumbHeight, $mainWidth, $mainHeight);

    switch($extn) {
        case ".gif" : imagegif($myThumbnail, $img_thumb_file) or die('Problem In Saving');
                      break;

        case ".jpg" : imagejpeg($myThumbnail, $img_thumb_file) or die('Problem In Saving');
                      break;
        case ".jpeg" : imagejpeg($myThumbnail, $img_thumb_file) or die('Problem In Saving');
                      break;

        case ".png" : imagepng($myThumbnail, $img_thumb_file) or die('Problem In Saving');
                      break;
    }

    imagedestroy($myThumbnail);
    imagedestroy($mainImage);

}



function create_random_password() {
  $chars = "abcdefghijkmnopqrstuvwxyz023456789";
  srand((double)microtime()*1000000);
  $i = 0;
  $pass = '';
  while ($i <= 7) {
    $num = rand() % 33;
    $tmp = substr($chars, $num, 1);
    $pass = $pass . $tmp;
    $i++;
  }
  return $pass;
}

//-------
function get_date($date){
  if($date != "") {
    if($date != '0000-00-00' && $date != '0000-00-00 00:00:00'){
      if (strlen($date)==19){
        $date = substr($date,0,10);
      }
      $ar = split('-',$date);
      return date("d-M-Y", mktime(0, 0, 0, $ar[1], $ar[2], $ar[0]));
    }
  }
}

function create_year_combo($start=2008, $end=1931, $default=false) {

  if($default) $year_dom[''] = '- Any Year -';
  for($i=$start; $i > $end; $i--) {
    $year_dom[$i] = $i;
  }
  return $year_dom;
}

function create_number_combo($start=0, $end=31, $default=false) {
  if($default) $number_dom[''] = '- Any -';
  for($i=$start; $i <= $end; $i++) {
    $number_dom[$i] = $i;
  }
  return $number_dom;
}

function scramble_html($source) {
  $source = str_replace("\t", "", $source);
  $s = "<script>document.write(unescape('";
  for($i=0; ($i < strlen($source)); $i++) {
    $a = ord(substr($source, $i, 1));
    if(!($a == 10 || $a == 13)) $s .= "%" . dechex($a);
  }
  $s .= "'));</script>";
  return $s;
}

function aw_redirect( $url) {
  /*
   * If the headers have been sent, then we cannot send an additional location header
   * so we will output a javascript redirect statement.
   */
  if (headers_sent()) {
    echo "<script>document.location.href='$url';</script>\n";
  } else {
    header( "Location: ". $url );exit();
  }
}

function random_password() {
  $chars = array(
    "a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J", "k","K","l","L","m","M","n",
    "N","o","O","p","P","q","Q","r","R","s","S","t","T", "u","U","v","V","w","W","x","X","y","Y","z","Z","1","2",
    "3","4","5","6","7","8","9","0");

  $max_chars = count($chars) - 1;
  srand((double)microtime()*1000000);

  for($i = 0; $i < 8; $i++){
    // $new_pass = ($i == 0) ? $chars[rand(0, $max_chars)] : $new_pass . $chars[rand(0, $max_chars)];
    $new_pass .= $chars[rand(0, $max_chars)];
  }
  return $new_pass;
}

function microtime_diff($a, $b) {
  list($a_dec, $a_sec) = explode(" ", $a);
  list($b_dec, $b_sec) = explode(" ", $b);
  return $b_sec - $a_sec + $b_dec - $a_dec;
}


function multiple_list_with_id($row_list, $selected="", $blank_row=0, $text='Multiple Select') {
  if($blank_row) $ret = "<option value=\"\" >$text</option>";
  //if($row_list) foreach($row_list as $row) $ret .= "<option value=\"". $row["id"] ."\"". ( ($row["id"]==$selected) ? " selected" : "" ) .">". $row["name"] ."</option>";
  foreach($row_list as $row) {
    $ret .= "<option value=".$row->id."";
    if(is_array($selected)) if(in_array($row->id, $selected)) $ret .= " selected";
    else if($row->id == $selected) $ret .= " selected";
    $ret .= ">". $row->name ."</option>";
  }
  return $ret;
}


function fileSizeinfo($fs) {
 $bytes = array('KB', 'KB', 'mb', 'GB', 'TB');
 // values are always displayed in at least 1 kilobyte:
 if ($fs <= 999) {
  $fs = 1;
 }
 for ($i = 0; $fs > 999; $i++) {
  $fs /= 1024;
 }
 return array(ceil($fs), $bytes[$i]);
}

function select_list_option_group($row, $selected="", $blank_row=0) {
  if($blank_row) $ret = "<option value=\"\"></option>";

  foreach($row as $k => $v){
    $ret .= "<optgroup label=\"$k\" >";
       foreach($v['val'] as $a => $av) {

          $ret .= "<option value=\"$a\"". ( ($a == $selected) ? " selected" : "" ) .">$av</option>";
       }
    $ret .= "</optgroup>";
  }

  return $ret;
}

function media_pre_processor($content) {

  require_once("include/classes/Media.php");
  require_once("include/simple_html_dom.php");

  global $media_path_site, $media_path_s3;

  $media_obj = new Media();

  $content = str_replace("data-image-name", "data_image_name", $content);

  $html = str_get_html($content);

  if($html == "") return $content;

  // print2($html->find('img')); die;

  foreach($html->find('img') as $tag) {

    $image_details = $media_obj->get_media_by_name($tag->data_image_name);

    if($image_details) {
      if($image_details->at_s3 == 1) {
        $src = $tag->src;
        $new_path = str_replace($media_path_site, $media_path_s3, $src);
        $content = str_replace($src, $new_path, $content);
      }
    }

  }

  $content = str_replace("data_image_name", "data-image-name", $content);

  return $content;

}


function media_pre_processor_new($content) {

  require_once("include/classes/Media.php");
  require_once("include/simple_html_dom.php");

  global $media_path_site, $media_path_s3;

  $img_array = array();
  preg_match_all('/<img[^>]+>/i',$content, $img_array); 

  if(!empty($img_array)) {
    foreach ($img_array as $img_val) {
       $res = str_replace($media_path_site, $media_path_s3, $img_val);
       print2($res); die;
    }
  }




  $media_obj = new Media();

  $content = str_replace("data-image-name", "data_image_name", $content);

  $html = str_get_html($content);

  if($html == "") return $content;

  foreach($html->find('img') as $tag) {

    $image_details = $media_obj->get_media_by_name($tag->data_image_name);

    if($image_details) {
      if($image_details->at_s3 == 1) {
        $src = $tag->src;
        $new_path = str_replace($media_path_site, $media_path_s3, $src);
        $content = str_replace($src, $new_path, $content);
      }
    }

  }

  $content = str_replace("data_image_name", "data-image-name", $content);

  return $content;

}

function objectToArray($d) {
  if (is_object($d)) $d = get_object_vars($d);
  return is_array($d) ? array_map(__METHOD__, $d) : $d;
}

?>