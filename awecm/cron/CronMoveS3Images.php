<?php define("__URBAN_OFFICE__", true);

# Purpose: Move images from local server to S3
# get files from media table and update at_s3 column in table after file moved to S3

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

ini_set('display_errors', 'On');
ini_set('default_charset', 'UTF-8');

$start_time = microtime();
$app_path = dirname(__FILE__);
$app_path = substr( $app_path, 0, strripos($app_path, DIRECTORY_SEPARATOR) );

set_include_path(get_include_path() . PATH_SEPARATOR . $app_path);

# Inclde necessary files
require_once('config.php');
require_once("include/functions.inc.php");
require_once('include/strings.inc.php');
require_once("include/classes/Media.php");

$focus = new Media();

$q_get_s3_images = "SELECT id, name, path
    FROM media
    WHERE at_s3=1";

$rs_get_s3_images =  $db->get_results($q_get_s3_images, ARRAY_A);

if(!empty($rs_get_s3_images)) {

  $replace_array = array(
    "page"             => "content",
  );

  foreach ($rs_get_s3_images as $row) {

    $replace_path_from = $media_path_site.$row['path'].$row['name'];
    $replace_path_to   = $media_path_s3.$row['path'].$row['name'];

    foreach ($replace_array as $tbl_name => $fld_name) {

      $q_update = '';
      $q_update = "UPDATE $tbl_name SET $fld_name = REPLACE($fld_name,'$replace_path_from', '$replace_path_to')";

      //$db->query($q_update);
    }
  }


}