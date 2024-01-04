<?php define("__URBAN_OFFICE__", true);

# Purpose: Cron file to compress the media images
$app_path = substr( $app_path, 0, strripos($app_path, DIRECTORY_SEPARATOR) );

set_include_path(get_include_path() . PATH_SEPARATOR . $app_path);

# Inclde necessary files
require_once('config.php');
require_once("include/functions.inc.php");
require_once('include/strings.inc.php');
require_once("include/classes/Media.php");

$obj_media = new Media();

# Get the media data
$media_data = $obj_media->get_media_attachments(1);

$media_size_to_compress = array(
  "555x415",
  "375x200",
  "340x280",
  "312x170",
  "795x400",
);

if( count($media_data) ==0 ) die("No Records Found !!!");

# Get all the image sizes
// $image_sizes = $image_size_dom['thumbnails'];
// $image_sizes_cropped = $image_size_dom['crop'];


$image_sizes = $media_size_to_compress;
foreach ($media_data as $row) {

  $file_path = $row['path'].$row['name'];
  $file_path2 = $row['path'];

  $file_extension = '.'.pathinfo($row['name'], PATHINFO_EXTENSION);
  $base_file_name = basename($row['name'], ".".pathinfo($row['name'], PATHINFO_EXTENSION));

  //$media_path = $media_path_site.$file_path;

  // $output_img_path = compress_image($cron_catalog_media_path.$file_path, $cron_catalog_media_path.$file_path);

  // if($output_img_path) $dwn_data = download($output_img_path, $cron_catalog_media_path.$file_path);

  # compress all thumbnail images
  foreach ($image_sizes as $image_size) {

    list($w, $h) = explode("x", $image_size);
    $thumb_dimension = $image_size;
    $thumb_image_name = $base_file_name."-".$thumb_dimension.$file_extension;

    $thumb_path = $row['path'].$thumb_image_name;

    $output_img_path = compress_image($cron_catalog_media_path.$thumb_path, $cron_catalog_media_path.$thumb_path);

    if($output_img_path) $dwn_data = download($output_img_path, $cron_catalog_media_path.$thumb_path);

  }

  # compress all cropped images
  // foreach ($image_sizes_cropped as $image_size) {

  //   list($w, $h) = explode("x", $image_size);
  //   $thumb_dimension = $image_size;
  //   $thumb_image_name = $base_file_name."-".$thumb_dimension.$file_extension;

  //   $thumb_path = $row['path'].$thumb_image_name;

  //   $output_img_path = compress_image($cron_catalog_media_path.$thumb_path, $cron_catalog_media_path.$thumb_path);

  //   if($output_img_path) $dwn_data = download($output_img_path, $cron_catalog_media_path.$thumb_path);

  // }
}

# Compress the images
function compress_image($source, $destination){
  $return_val = 0;

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api.tinify.com/shrink");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($source));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_USERPWD, 'api' . ':' . 'BCFcyjHSTZd8DqswMQ87Zw27DQbynYqx');

  $headers = array();
  $headers[] = 'Content-Type: application/x-www-form-urlencoded';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);

  if ($response === false) $response = curl_error($ch);
  else {
    $return_val = 1;

    $response_data = json_decode($response,1);
    curl_close ($ch);
    return $response_data['output']['url'];
  }
 
  if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
  }

  curl_close ($ch);

  return $return_val;

}

# Download the image on the destination path
function download($source,$destination) {

  $ch = curl_init();

  //Set options for curl object.
  $curlOptions = array(
    CURLOPT_URL => $source,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => 0
  );
  curl_setopt_array($ch, $curlOptions);

  $response = curl_exec($ch);
  curl_close ($ch);

  //Get file content and save on specified file path.
  return (file_put_contents($destination, $response) !== false);

  //Return the state.
  return false;
}