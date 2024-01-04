<?php if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

header("Pragma: no-cache");
 
if(!empty($post_type)) {
    
    header( 'Content-Type: text/html; charset=utf-8' );
} else if(check_uri_by_slash($current_full_url)) {
  $rdir = $current_full_url."/";
  // include_once("stubs/Home/301.php");
  // exit;
} else {
   $awecm_url_info = parse_url($current_full_url); 
  
  if(!strstr($awecm_url_info['host'], $domain_name)) {
    $rdir = str_replace($awecm_url_info['host'], $domain_name, $current_full_url);
    $rdir = str_replace("http://", "https://", $rdir);
    // include_once("stubs/Home/301.php");
    // exit;
  }

   header("HTTP/1.0 404 Not Found");
}

ini_set('default_charset', 'UTF-8');
ini_set('display_errors',  1);

$template_selected = "";

include("include/header.inc.php");
include("include/top.inc.php");

if($post_type == 'page') {

  require_once("include/classes/Page.php");
  $focus = new Page();

  $page = $focus->get_page_by_uri($current_full_url);

  $is_template = false;

  if($template_dom) {
    foreach ($template_dom as $tag => $dom) {
      if(!empty($page) && strstr($page->tag, $tag)) {
        $is_template = true;
        $template_selected = $tag;
        include_once("stubs/Home/".$template_action_dom[$tag].".php");
      }
    }
  }

  if(!empty($page) && $page->post_type == "property") {
    $is_property = true;
    include_once("stubs/Home/Property.php");
  } else {
    if(!$is_template) include_once("stubs/Home/Page.php");
  }

} else {
      
  include_once("stubs/Home/404_Page.php");
}

include("include/footer.inc.php");