<?php define("__URBAN_OFFICE__", true);

# Purpose: Regenerating 

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

ini_set('display_errors', 'On');
ini_set('default_charset', 'UTF-8');

$start_time = microtime();
$app_path = dirname(__FILE__);

$app_path = substr( $app_path, 0, strripos($app_path, DIRECTORY_SEPARATOR) );

set_include_path(get_include_path() . PATH_SEPARATOR . $app_path);


# Inclde necessary files
require_once('config.php');
require_once("include/img_lib.inc.php");
require_once("include/functions.inc.php");
require_once('include/strings.inc.php');
require_once("include/classes/Media.php");

$focus = new Media();

$rs = $focus->get_media_attachments();

if( count($rs) ==0 ) die("No Records Found !!!");

# Process file copy to S3 #
$image_sizes_thumbnails = $image_size_dom['thumbnails'];
$image_sizes_crop = $image_size_dom['crop'];

$appended_path = "public_html/".$uploaded_image_path;

foreach ($rs as $row) {

  $file_path = $row['path'].$row['name'];

  echo "File Path : ". $appended_path.$file_path.EOL;

  $original_image_path = $appended_path.$file_path; 

  $size = @getimagesize($original_image_path);

  $file_extension = '.'.pathinfo($row['name'], PATHINFO_EXTENSION);
  $base_file_name = basename($row['name'], ".".pathinfo($row['name'], PATHINFO_EXTENSION));

  # Croping images
  foreach ($image_sizes_crop as $image_size) {

    list($w, $h) = explode("x", $image_size);
    $thumb_dimension = $image_size;
    $thumb_image_name = $base_file_name."-".$thumb_dimension.$file_extension;

    $thumb_path = $appended_path.$row['path'].$thumb_image_name;

    if(file_exists($thumb_path)) {
      unlink($thumb_path);
    }

    if(($size[0] > $w) || ($size[1] > $h)) {
      crop_image_from_center($w, $h, $file_extension, $original_image_path, $thumb_path, "");
    }
    else {
      copy($original_image_path, $thumb_path);
    }
  
    echo "Thumb Path : ".$thumb_path.EOL;

  }

  foreach ($image_sizes_thumbnails as $image_size) {

    list($w, $h) = explode("x", $image_size);
    $thumb_dimension = $image_size;
    $thumb_image_name = $base_file_name."-".$thumb_dimension.$file_extension;

    $thumb_path = $appended_path.$row['path'].$thumb_image_name;

    if(file_exists($thumb_path)) {
      unlink($thumb_path);
    }

    if(($size[0] > $w) || ($size[1] > $h)) {
      img_resize($original_image_path, $thumb_path, $w, $h);
    }
    else {
      copy($original_image_path, $thumb_path);
    }
  
    echo "Thumb Path : ".$thumb_path.EOL;

  }

  
}
