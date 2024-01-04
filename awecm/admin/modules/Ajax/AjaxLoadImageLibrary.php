<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once('include/classes/Media.php');

$focus = new Media();
$images = $focus->get_media_attachments($limit=100);

foreach ($images as $key => $image) {

  $images[$key]['thumbnails'] = get_all_thumbnails_path($image['path'], $image['name']);
  $images[$key]['global_thumbnails_path'] = get_all_global_thumbnails_path($image['path'], $image['name']);
  $images[$key]['original_image'] = get_image_absolute_path($image['path'], $image['name']);
  $images[$key]['thumbnail_sizes'] = $image_size_dom['thumbnails'];
  $images[$key]['global_image_path'] = get_global_image_path($image['path'], $image['name']);

}

echo json_encode($images);
