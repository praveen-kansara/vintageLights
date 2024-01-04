<?php

# Purpose: Loader File
define("__URBAN_OFFICE__", true);

session_start();
$app_path = dirname(__FILE__);

$paths = array(
  $app_path
);

// ini_set('display_errors', 0);

set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths));

$startTime = microtime();

if(!isset($stub) || $stub == "") $stub = 'Pages';
if(!isset($action) || $action == "") $action = 'index';

# Include Required Files
require_once("config.php");
require_once("include/xtpl.php");
require_once("include/utils.inc.php");
require_once("include/functions.inc.php");
require_once("include/modules.inc.php");
require_once("include/strings.inc.php");
require_once("include/navigate.inc.php");
require_once("include/logger.php");
require_once("include/site.inc.php");

$disable_layout = true;

$log = New Logger("root");

# Define http protocol
if($_SERVER['SERVER_PORT'] == 443) $http_protocol = 'https://';
else $http_protocol = 'http://';

# Define root path
$root_path = $http_protocol.$_SERVER['HTTP_HOST'].( (dirname($_SERVER['PHP_SELF'])) ? dirname($_SERVER['PHP_SELF']) : "" );

# Ignoring the query parameters
$base_uri          = strtok($_SERVER["REQUEST_URI"],'?');
echo $current_full_url  = $http_protocol."$_SERVER[HTTP_HOST]$base_uri";
$post_type         = check_post_type_by_uri($current_full_url);

$no_cache = false;

#================
# Get query params
$q = (isset($_REQUEST['q']) ? $_REQUEST['q'] : null);

$q_array = array();

if($q) $q_array = explode("/", $q);

$stub   = ((isset($q_array[0]) && ($q_array[0] !='')) ? $q_array[0] : "Home");
$action = ((isset($q_array[1]) && ($q_array[1] !='')) ? $q_array[1] : "index");
#================

# Disable caching for a projects & Ajax files
$file_path = "stubs/".$stub."/".$action.".php";
$ajax_file = stristr($stub, 'Ajax');



#=====================================
# Set flag to cache Specific Ajax files
$cache_ajax_file = false;

# Set the disable cache flag
$disable_cache = (false || ($ajax_file !== false && $cache_ajax_file == false));

# Create base 64 URL format for caching
$encoded_url = base64_encode($current_full_url);
$cache_file_path .= $encoded_url.".htm";
#===================================
# Redirect conditions to other URLS
if(!file_exists($cache_file_path)) {


  # redirect URL if any
  if($current_full_url) {
    if(is_file($rdir_json)) {
      $file_json  = file_get_contents($rdir_json);
      $rdir_urls  = json_decode($file_json);

      foreach ($rdir_urls as $rdir_url) {

        if($rdir_url->uri == $current_full_url) {

          $rdir = $rdir_url->redir;
          count_url_hit($rdir_url->id);
          include_once("stubs/Home/$rdir_url->redir_type.php");
          exit;

        } else if(strstr($rdir_url->uri, "(*)")){
          $rdir_url->uri = str_replace("(*)", "", $rdir_url->uri);

          if(strstr($current_full_url, $rdir_url->uri)) {

            $rdir = $rdir_url->redir;
            count_url_hit($rdir_url->id);
            include_once("stubs/Home/$rdir_url->redir_type.php");
            exit;

          }
        }
      }
    }
  }
}


$has_form = stristr($current_full_url, 'application-form');

if($has_form !== false) $disable_cache = true;

$disable_cache = true;
#===========================
# Create Cache of the files
if($disable_cache || !$post_type) {
 
  if(is_file( $app_path.'/'.$file_path )) include($file_path);

} else if($cache_ajax_file ){ // create cache for Ajax file


  $cache_file_url      = $current_full_url."?q=".$q;
  $encoded_cache_url   = base64_encode($cache_file_url);
  $ajx_cache_file_path = "../awecm/cache/".$encoded_cache_url.".htm";

  if(!file_exists($ajx_cache_file_path) && is_file( $app_path.'/'.$file_path )) {

    ob_start();
    $file_path = "stubs/".$stub."/".$action.".php";
    include($file_path);

    try {
      $minified_content = sanitize_output(ob_get_contents());
      file_put_contents($ajx_cache_file_path, $minified_content);

    } catch(Exception $e) {}
    ob_get_clean();
    if (ob_get_length() > 0) ob_end_flush();

  }
  include($ajx_cache_file_path);

} else {

  if(!file_exists($cache_file_path)) {

    ob_start();
    $file_path = "stubs/".$stub."/".$action.".php";

    if(is_file( $app_path.'/'.$file_path )) include($file_path);

    try {
      $minified_content = sanitize_output(ob_get_contents());
      file_put_contents($cache_file_path, $minified_content);

    } catch(Exception $e) {}

    ob_get_clean();
    if (ob_get_length() > 0) ob_end_flush();
  }

  include($cache_file_path);
}