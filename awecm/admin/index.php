<?php
define("__URBAN_OFFICE__", true);

ini_set('display_errors', 1);

 $app_path = dirname(__FILE__);

 $app_path = substr( $app_path, 0, strripos($app_path, DIRECTORY_SEPARATOR) );

 set_include_path(get_include_path() . PATH_SEPARATOR . $app_path);

 set_include_path(get_include_path() . PATH_SEPARATOR . $app_path . '/admin');

$startTime = microtime();

session_start();

require_once("config.php");
require_once("include/xtpl.php");
require_once("include/utils.inc.php");
require_once("include/site.inc.php");
require_once("include/constants.inc.php");
require_once("include/navigate.inc.php");
require_once("include/logger.php");
require_once("include/strings.inc.php");
require_once("include/functions.inc.php");
require_once("admin/include/modules.inc.php");

$admin_current_full_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


$log = New Logger("root");
$current_user_id    = isset($_SESSION['current_user_id']) ? $_SESSION['current_user_id'] : null;
$admin_root_path = "http://" .$_SERVER['HTTP_HOST']. ((dirname($_SERVER['PHP_SELF']) != "/") ? dirname($_SERVER['PHP_SELF'])."/" : "");

if(empty($current_user_id) && isset($_COOKIE['urbanoffice_auth_key'])) {
  if(CookieAuthenticate($_COOKIE['urbanoffice_auth_key'])) {
    redirect($admin_root_path);
  }
}

$module = !empty($_REQUEST['module']) ? $_REQUEST['module'] : 'Property';
$action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : "index";

if(!isset($_SESSION['current_user_id']) && $action != "Authenticate") {
  $module = 'SystemUser';
  $action = 'Login';
}
else {
  if(isset($_SESSION['current_user_id'])) {
    $current_user_id = $_SESSION['current_user_id'];
  }
}

$delete = strstr($action, "Delete");
$save   = strstr($action, "Save");
$ajax   = strstr($module, "Ajax");

$disable_layout = ( $delete !== false
||  $save   !== false
||  $ajax   !== false

||  $action  == "Logout"
||  $action  == "CheckSlug"
||  $action  == "Login"
||  $action  == "Authenticate"
||  $action  == "AddNewSlot"
||  $action  == "AjaxNoOfAd"
||  $action  == "AjaxMarkRead"
||  $action  == "EnquiryCSVExport"
||  $action  == "PurgeCache"
||  $action  == "Minify"
||  $action  == "UpdateTag"
);


if(!$disable_layout) {
  include("admin/include/header.inc.php");
  include("admin/include/top.inc.php");
}

// For initial declaration of variables.
include('include/variable.inc.php');

$file_path = "admin/modules/".$module."/".$action.".php";

if(is_file( $app_path.'/'.$file_path )) {
  include( $file_path );
}
else {
  include( "admin/modules/Home/404_Page.php" );
}
if(!$disable_layout) include("admin/include/footer.inc.php");
