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

$img_action = 'save';

$rs = $focus->get_nons3_images(200);


if( count($rs) ==0 ) die("No Records Found !!!");

# Process file copy to S3 #
$image_sizes = $image_size_dom['thumbnails'];
$image_sizes_cropped = $image_size_dom['crop'];

foreach ($rs as $row) {

  $new_upload = true;
  $file_path = $row['path'].$row['name'];

  $new_upload = S3_image_handler($img_action, $file_path);

  # Processing original file
  if ($new_upload) {
    echo "Image $file_path uploaded successfully". EOL;
    $focus->update_s3_flag($row['id'], 1);

    # Replace the local media path in the tables from s3
    $q = "SELECT name, path FROM media WHERE id = '".$row['id']."' LIMIT 1";
    $rs = $db->get_row($q);

    if($rs) {

      $replace_array = array(
        "page" => "content",
      );

      $replace_path_from = $media_path_site.$rs->path.$rs->name;
      $replace_path_to   = $media_path_s3.$rs->path.$rs->name;

      foreach ($replace_array as $tbl_name => $fld_name) {

        $q_update = '';
        $q_update = "UPDATE $tbl_name SET $fld_name = REPLACE($fld_name,'$replace_path_from', '$replace_path_to')";

        echo $q_update . EOL;

        $db->query($q_update);
      }
    }

  }
  
  $file_extension = '.'.pathinfo($row['name'], PATHINFO_EXTENSION);
  $base_file_name = basename($row['name'], ".".pathinfo($row['name'], PATHINFO_EXTENSION));

  foreach ($image_sizes as $image_size) {

    list($w, $h) = explode("x", $image_size);
    $thumb_dimension = $image_size;
    $thumb_image_name = $base_file_name."-".$thumb_dimension.$file_extension;

    $thumb_path = $row['path'].$thumb_image_name;

    $new_upload = S3_image_handler($img_action, $thumb_path);
    # Processing thumbnail files
    if ($new_upload) {
      echo "Thumbnail Image $thumb_path uploaded successfully". EOL;
    }

  }

  foreach ($image_sizes_cropped as $image_size) {

    list($w, $h) = explode("x", $image_size);
    $thumb_dimension = $image_size;
    $thumb_image_name = $base_file_name."-".$thumb_dimension.$file_extension;

    $thumb_path = $row['path'].$thumb_image_name;

    $new_upload = S3_image_handler($img_action, $thumb_path);
    # Processing thumbnail files
    if ($new_upload) {
      echo "Thumbnail Image $thumb_path uploaded successfully". EOL;
    }

  }

  
}
