<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

if($_FILES['file']['name']) {

  require_once("include/functions.inc.php");

  $file_name = prettify_image_name($_FILES['file']['name']);

  # Getting extension of uploaded file
  $file_extension = '.'.pathinfo($file_name, PATHINFO_EXTENSION);

  if(in_array(strtolower($file_extension), array('pdf'))) {

    $base_file_name = basename($file_name, ".".pathinfo($file_name, PATHINFO_EXTENSION));

    # Creating directories in file path directory
    $path = "../".trim($uploaded_image_path);
    $md5  = md5($base_file_name);

    for($i = 0; $i < 6; $i++) {
      $path = $path . $md5{$i} . '/';
      if(!is_dir($path)) {
        @mkdir($path);
        @chmod($path, 0777);
      }
    }

    $full_path = $path.$base_file_name.$file_extension;

    $path_only = str_replace("../media/","",$path);

    if(move_uploaded_file($_FILES['file']['tmp_name'], $full_path)) {

      $response = array(
        'status'             => 'success',
        'original_file_name' => $base_file_name,
        'msg'                => 'Uploaded successfully',
        'file_path'          => $site_url.$uploaded_image_path.$path_only
      );

    } else {
      $response = array('status'=>'error', 'msg'=> 'Error occurred while uploading the file.');
    }
  } else {
    $response = array('status'=>'error', 'msg'=> 'Please upload a PDF file');
  }
} else {
  $response = array('status'=>'error', 'msg'=> 'File not found.');
}

echo json_encode($response);
