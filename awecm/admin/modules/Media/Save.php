<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');


if($_FILES['file']['name']) {

  require_once("include/img_lib.inc.php");
  require_once("include/functions.inc.php");
  require_once("include/classes/Media.php");

  $image_name = prettify_image_name($_FILES['file']['name']);

  # Getting extension of uploaded file
  $file_extension = '.'.pathinfo($image_name, PATHINFO_EXTENSION);

  if(in_array(strtolower($file_extension), $image_extn_dom)) {

    $base_file_name = basename($image_name, ".".pathinfo($image_name, PATHINFO_EXTENSION));

    # checking for duplicate name in database
    $focus = new Media();
    $counts = $focus->get_image_name_counts($base_file_name);
    if($counts!=0) {
      $base_file_name = $base_file_name."-".($counts+1);
    }

    # Creating directories in image path directory
    $path = "../".trim($uploaded_image_path);
    $md5  = md5($base_file_name);

    for($i = 0; $i < 6; $i++) {
      $path = $path . $md5[$i] . '/';
      if(!is_dir($path)) {
        @mkdir($path, 0777, true);
        @chmod($path, 0777);
      }
    }

    $full_path = $path.$base_file_name.$file_extension;

    $path_only = str_replace("../media/","",$path);

    $original_image_name = get_image_absolute_path($path_only, $base_file_name.$file_extension);
    $global_image_path = get_global_image_path($path_only, $base_file_name.$file_extension  );

    $focus->name = $base_file_name.$file_extension;
    $focus->path = $path_only;
    $focus->save();

    if(move_uploaded_file($_FILES['file']['tmp_name'], $full_path)) {

      # Saving image in required dimensions
      $size = getimagesize($full_path);
      $image_sizes = $image_size_dom['thumbnails'];
      $image_sizes_crop = $image_size_dom['crop'];
      $thumbnails  = [];
      $_thumbnails = [];

      foreach ($image_sizes as $image_size) {
        /* Image size and new image name */
        list($w, $h) = explode("x", $image_size);
        $thumb_dimension = $image_size;
        $thumb_image_name = $base_file_name."-".$thumb_dimension.$file_extension;

        $thumb_path = $path.$thumb_image_name;

        /* Resize or copy for new dimension */
        if(($size[0] > $w) || ($size[1] > $h)) {
          img_resize($full_path, $thumb_path, $w, $h);
        }
        else {
          copy($full_path, $thumb_path);
        }
        //$thumbnails[] = $thumb_image_name;
        //$_thumbnails[] = $site_url.$uploaded_image_path.str_replace("../media/","",$thumb_path);
      }

      foreach ($image_sizes_crop as $image_size) {
        /* Image size and new image name */
        list($w, $h) = explode("x", $image_size);
        $thumb_dimension = $image_size;
        $thumb_image_name = $base_file_name."-".$thumb_dimension.$file_extension;

        $thumb_path = $path.$thumb_image_name;

        /* Resize or copy for new dimension */
        if(($size[0] > $w) || ($size[1] > $h)) {
          crop_image_from_center($w, $h, $file_extension, $full_path, $thumb_path, "");
        }
        else {
          copy($full_path, $thumb_path);
        }
        //$thumbnails[] = $thumb_image_name;
        //$_thumbnails[] = $site_url.$uploaded_image_path.str_replace("../media/","",$thumb_path);
      }

      # creating meta data of the uploaded image

      list($width, $height) = getimagesize($full_path);

      $raw_data = json_encode([
        'width' => $width,
        'height' => $height,
        'type' => pathinfo($full_path, PATHINFO_EXTENSION),
        "caption" => "",
        "alt" => "",
      ]);

      $focus->attributes =  $raw_data;
      $focus->save();
      echo json_encode([
        'status'=>'ok', 'attributes'=> $raw_data, 'original_image'=>$base_file_name, 'msg'=> 'Uploaded successfully',
        'image_path'=>$site_url.$uploaded_image_path.$focus->path, 'id'=> $focus->id, 'name'=>$focus->name,
        'original_image'=>$original_image_name, 'thumbnails'=>get_all_thumbnails_path($path_only, $base_file_name.$file_extension),
        'thumbnail_sizes'=>$image_size_dom['thumbnails'], 'global_image_path' => $global_image_path,
        'global_thumbnails_path' => get_all_global_thumbnails_path($path_only, $base_file_name.$file_extension)
      ]);

    }
    else {
      echo json_encode(['status'=>'error', 'msg'=> 'Error occured.']);
    }

  }

}
