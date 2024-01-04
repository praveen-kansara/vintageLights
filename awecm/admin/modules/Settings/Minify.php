<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/minifier.php");

$cache_min_file_path = "../static/front/min/";

$min_array = array(
  # page.min.css 
  array(
    "file_name" => "page.min.css",
    "files" => array(
     ),
  ),

  # home.min.js
  array(
    "file_name" => "home.min.js",
    "files" => array(
      $front_static_files_path."js/jquery-3.6.0.min.js",
      $front_static_files_path."js/bootstrap.min.js",
      $front_static_files_path."js/slick.min.js",
      $front_static_files_path.'js/jquery.validate.min.js',
      $front_static_files_path.'js/jquery-ui.js',
      $front_static_files_path."js/main.js",
      $front_static_files_path."js/bootstrap-datepicker.js",
    ),
  ),

  # page.min.js
  array(
    "file_name" => "page.min.js",
    "files" => array(

      $front_static_files_path."js/jquery-3.6.0.min.js",
      $front_static_files_path."js/bootstrap.min.js",
      $front_static_files_path.'js/jquery.validate.min.js',
      $front_static_files_path.'js/jquery-ui.js',
      $front_static_files_path.'js/panzoom-jquery.js',
      $front_static_files_path.'js/property.js',
      $front_static_files_path.'min/contact.min.js',
      $front_static_files_path."js/slick.min.js",
      $front_static_files_path."js/hc-sticky.js",
      $front_static_files_path."js/main.js",
      $front_static_files_path."js/bootstrap-datepicker.js",
     ),
  ),
);

foreach($min_array as $each_file) {
  ob_start();
  foreach($each_file['files'] as $file) {
    echo "\n";
    include($file);
  }

  $content = ob_get_contents();
  file_put_contents($cache_min_file_path.$each_file['file_name'], $content);
  ob_get_clean();
}

$return_url = "./?module=Settings&action=EditView";

redirect($return_url);