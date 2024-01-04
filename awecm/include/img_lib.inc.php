<?php
/*
*  @get_image_size function returns image size of the required image
*  with respect to original image ratio
*/
function get_image_size ($orig_size, $req_width, $req_height=0) {

  $size_array = array();

  if ($orig_size) {
    list($w_orig, $h_orig) = explode("x", $orig_size);

    $scale_ratio = $w_orig / $h_orig;

    ##  Default return  ##
    $w = $w_orig;
    $h = $h_orig;

    if( $req_height && (($w_orig > $req_width) || ($h_orig > $req_height)) ) {
      if (($req_width / $req_height) > $scale_ratio) {
        $w = $req_height * $scale_ratio;
        $h = $req_height;
      }
      else {
        $w = $req_width;
        $h = $req_width / $scale_ratio;
      }
    }
    elseif ($w_orig > $req_width) { ##  Auto height image size  ##
      $w = $req_width;
      $h = $req_width / $scale_ratio;
    }

    $size_array[0] = floor($w);
    $size_array[1] = floor($h);
  }
  return $size_array;
}


/**
**  Resize image using ImageMagic class
*/
function img_resize_crop_imagic( $target, $newcopy, $width, $height ) {
  $thumb = new Imagick($target);

  $imageprops = $thumb->getImageGeometry();
  $w_orig = $imageprops['width'];
  $h_orig = $imageprops['height'];

  if ($w_orig == $h_orig) {
    $h = $width;
    $w = $height;
  }
  elseif($w_orig > $h_orig && ($h_orig > $height)) {
    $h = $height;
    $w = ($height / $h_orig) * $w_orig;
  }
  elseif($h_orig > $w_orig && ($w_orig > $width)) {
    $w = $width;
    $h = ($width / $w_orig) * $h_orig;
  }
  else {
    $h = $h_orig;
    $w = $w_orig;
  }

  $center_x = ($w > $width) ? ($w - $width)/2 : 0;
  $center_y = ($h > $height) ? ($h - $height)/2 : 0;

  $thumb->resizeImage($w,$h,Imagick::FILTER_LANCZOS,1);
  $thumb->cropImage ($width,$height,$center_x,$center_y);
  $thumb->setImageCompression(Imagick::COMPRESSION_JPEG);
  $thumb->setImageCompressionQuality(0);
  $thumb->writeImage($newcopy);
  $thumb->clear();
  $thumb->destroy();
}


function img_resize_imagic( $target, $newcopy, $width, $height ) {
  $thumb = new Imagick();

  list($w_orig, $h_orig) = @getimagesize($target);

  if( ($w_orig > $width) || ($h_orig > $height) ) {

    $scale_ratio = $w_orig / $h_orig;

    if (($width / $height) > $scale_ratio) {
      $w = $height * $scale_ratio;
      $h = $height;
    }
    else {
      $h = $width / $scale_ratio;
      $w = $width;
    }
  } else {
    $h = $h_orig;
    $w = $w_orig;
  }

  $thumb->readImage($target);
  $thumb->resizeImage($w,$h,Imagick::FILTER_LANCZOS,1);
  $thumb->setImageCompression(Imagick::COMPRESSION_JPEG);
  $thumb->setImageCompressionQuality(0);
  $thumb->stripImage();
  $thumb->writeImage($newcopy);
  $thumb->clear();
  $thumb->destroy();
}


function img_resize_imagic_with_white_canvas( $target, $newcopy, $width, $height ) {
  $thumb = new Imagick($target);

  list($w_orig, $h_orig) = @getimagesize($target);

  if( ($w_orig > $width) || ($h_orig > $height) ) {

    $scale_ratio = $w_orig / $h_orig;

    if (($width / $height) > $scale_ratio) {
      $w = $height * $scale_ratio;
      $h = $height;
    }
    else {
      $h = $width / $scale_ratio;
      $w = $width;
    }
  } else {
    $h = $h_orig;
    $w = $w_orig;
  }

  $dst_x = ($width > $w) ? ($width - $w)/2 : 0;
  $dst_y = ($height > $h) ? ($height - $h)/2 : 0;

  $thumb->readImage($target);
  $thumb->resizeImage($w,$h,Imagick::FILTER_LANCZOS,1);
  $thumb->setImageCompression(Imagick::COMPRESSION_JPEG);
  $thumb->setImageCompressionQuality(0);

  ##  Create a new canvas object and a white image  ##
  $canvas = new Imagick();
  $canvas->newImage($width, $height, "white");
  ##  Composite the original image on the canvas  ##
  $canvas->compositeImage($thumb, Imagick::COMPOSITE_OVER, ceil($dst_x), ceil($dst_y));
  $canvas->setImageCompression(Imagick::COMPRESSION_JPEG);
  $canvas->setImageCompressionQuality(0);
  $canvas = $canvas->flattenImages();

  $canvas->writeImage($newcopy);
  $thumb->clear();
  $thumb->destroy();
  $canvas->clear();
  $canvas->destroy();
}

## This function is exectly same as img_resize_imagic()
function img_resize_imagic_fixed( $target, $newcopy, $width, $height ) {
  $thumb = new Imagick($target);

  list($w_orig, $h_orig) = @getimagesize($target);

  if( ($w_orig > $width) || ($h_orig > $height) ) {

    $scale_ratio = $w_orig / $h_orig;

    if (($width / $height) > $scale_ratio) {
      $w = $height * $scale_ratio;
      $h = $height;
    }
    else {
      $h = $width / $scale_ratio;
      $w = $width;
    }
  } else {
    $h = $h_orig;
    $w = $w_orig;
  }

  $thumb->readImage($target);
  $thumb->resizeImage($w,$h,Imagick::FILTER_LANCZOS,1);
  $thumb->setImageCompression(Imagick::COMPRESSION_JPEG);
  $thumb->setImageCompressionQuality(0);
  $thumb->stripImage();
  $thumb->writeImage($newcopy);
  $thumb->clear();
  $thumb->destroy();
}


function img_resize_imagic_fixed_width( $target, $newcopy, $width ) {
  $thumb = new Imagick($target);

  list($w_orig, $h_orig) = getimagesize($target);

  if ($w_orig > $width) {
    $scale_ratio = $w_orig / $h_orig;
    $w = $width;
    $h = $width / $scale_ratio;
  }
  else {
    $w = $w_orig;
    $h = $h_orig;
  }

  $thumb->readImage($target);

  $thumb->resizeImage($w,$h,Imagick::FILTER_LANCZOS,1);
  $thumb->setImageCompression(imagick::COMPRESSION_JPEG);
  $thumb->setImageCompressionQuality(0);
  $thumb->stripImage();
  $thumb->writeImage($newcopy);
  $thumb->clear();
  $thumb->destroy();
}

##  Resize cropped image with scale ##
function resizeCroppedImage_imagic($target, $newcopy, $w, $h, $dst_x, $dst_y, $scale) {
  $thumb = new Imagick($target);
  $thumb->setImageCompression(Imagick::COMPRESSION_JPEG);
  $thumb->setImageCompressionQuality(0);
  ########################## Alternate of below commented code
  $thumb->cropImage($w, $h, $dst_x, $dst_y);
  #    ##  Remove minus(-) sign from front of round with $dst_x & $dst_y for use in local server ##
  #    $thumb-> extentImage($w,$h,-round($dst_x),-round($dst_y));
  $thumb->writeImage($newcopy);
  $thumb->clear();
  $thumb->destroy();
  return $newcopy;
}

##  Normal image resizeImage  ##
function img_resize($target, $newcopy, $width, $height) {

  list($w_orig, $h_orig) = @getimagesize($target);

  if( ($w_orig > $width) || ($h_orig > $height) ) {

    $scale_ratio = $w_orig / $h_orig;

    if (($width / $height) > $scale_ratio) {
      $w = $height * $scale_ratio;
      $h = $height;
    }
    else {
      $h = $width / $scale_ratio;
      $w = $width;
    }
  } else {
    $h = $h_orig;
    $w = $w_orig;
  }

  $img = "";
  $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));

  if ($ext == "gif") $img = imagecreatefromgif($target);
  elseif ($ext == "png") $img = imagecreatefrompng($target);
  elseif ($ext == "webp") $img = imagecreatefromwebp($target);
  else $img = imagecreatefromjpeg($target);

  //imagefilter($img, IMG_FILTER_SMOOTH, IMG_FILTER_SMOOTH);

  $tci = imagecreatetruecolor($w, $h);
  if (exif_imagetype($target) == IMAGETYPE_PNG) imagealphablending($tci, false);

  // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
  imagecopyresampled($tci, $img, 0, 0, 0, 0, round($w), round($h), $w_orig, $h_orig);

  if (exif_imagetype($target) == IMAGETYPE_PNG) imagesavealpha($tci, true);

  // Free memory
  imagedestroy($img);

  //imagesharpen($tci);

  if ($ext == "gif") imagegif($tci, $newcopy);
  elseif ($ext == "png") imagepng($tci, $newcopy);
  elseif ($ext == "webp") imagewebp($tci, $newcopy);
  else imagejpeg($tci, $newcopy, 100);
}

##  Normal image resize with coverd white in blank  ##
function img_resize_fixed($target, $newcopy, $width, $height) {

  $tci = imagecreatetruecolor($width, $height);
  $bg = imagecolorallocate ( $tci, 255, 255, 255 );
  imagefilledrectangle($tci,0,0,$width,$height,$bg);

  list($w_orig, $h_orig) = @getimagesize($target);

  if( ($w_orig > $width) || ($h_orig > $height) ) {

    $scale_ratio = $w_orig / $h_orig;

    if (($width / $height) > $scale_ratio) {
      $w = $height * $scale_ratio;
      $h = $height;
    }
    else {
      $h = $width / $scale_ratio;
      $w = $width;
    }
  } else {
    $h = $h_orig;
    $w = $w_orig;
  }

  $dst_x = ($width > $w) ? ($width - $w)/2 : 0;
  $dst_y = ($height > $h) ? ($height - $h)/2 : 0;

  $img = "";
  $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));

  if ($ext == "gif") $img = imagecreatefromgif($target);
  elseif ($ext == "png") $img = imagecreatefrompng($target);
  elseif ($ext == "webp") $img = imagecreatefromwebp($target);
  else $img = imagecreatefromjpeg($target);

  imagefilter($img, IMG_FILTER_SMOOTH, IMG_FILTER_SMOOTH);

  //$tci = imagecreatetruecolor($w, $h);
  if (exif_imagetype($target) == IMAGETYPE_PNG) imagealphablending($tci, false);

  // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
  imagecopyresampled($tci, $img, round($dst_x), round($dst_y), 0, 0, round($w), round($h), $w_orig, $h_orig);

  if (exif_imagetype($target) == IMAGETYPE_PNG) imagesavealpha($tci, true);

  // Free memory
  imagedestroy($img);

  //imagesharpen($tci);

  if ($ext == "gif") imagegif($tci, $newcopy);
  elseif ($ext == "png") imagepng($tci, $newcopy);
  elseif ($ext == "webp") imagewebp($tci, $newcopy);
  else imagejpeg($tci, $newcopy, 100);
}


// Function to resize thumbnail image
function resizeThumbnailImage($target, $newcopy, $w, $h, $start_w, $start_h, $scale) {

  $tci_w = ceil($w * $scale);
  $tci_h = ceil($h * $scale);

  $tci = imagecreatetruecolor($tci_w, $tci_h);
  if (exif_imagetype($target) == IMAGETYPE_PNG) imagealphablending($tci, false);

  $img = "";
  $ext = strtolower(pathinfo($newcopy, PATHINFO_EXTENSION));

  if ($ext == "gif") $img = imagecreatefromgif($target);
  elseif ($ext == "png") $img = imagecreatefrompng($target);
  elseif ($ext == "webp") $img = imagecreatefromwebp($target);
  else $img = imagecreatefromjpeg($target);

  imagecopyresampled($tci,$img,0,0,$start_w,$start_h,$tci_w,$tci_h,$w,$h);

  if (exif_imagetype($target) == IMAGETYPE_PNG) imagesavealpha($tci, true);

  if ($ext == "gif") imagegif($tci, $newcopy);
  elseif ($ext == "png") imagepng($tci, $newcopy);
  elseif ($ext == "webp") imagewebp($tci, $newcopy);
  else imagejpeg($tci, $newcopy, 100);

  chmod($newcopy, 0644);
  return $newcopy;
  
}



// ------------------ IMAGE CONVERT FUNCTIONs -------------------
// Function for converting GIFs and JPGs to PNG
function img_convert_to_png($target, $newcopy) {

  list($w_orig, $h_orig) = @getimagesize($target);

  $img = "";
  $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));

  if ($ext == "gif") $img = imagecreatefromgif($target);
  elseif ($ext == "png") $img = imagecreatefrompng($target);
  elseif ($ext == "webp") $img = imagecreatefromwebp($target);
  else $img = imagecreatefromjpeg($target);

  $tci = imagecreatetruecolor($w_orig, $h_orig);
  if (exif_imagetype($target) == IMAGETYPE_PNG) imagealphablending($tci, false);

  imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);

  if (exif_imagetype($target) == IMAGETYPE_PNG) imagesavealpha($tci, true);

  // Free memory
  imagedestroy($img);

  imagepng($tci, $newcopy);
}


// ----------------------- BORDER FUNCTIONs -----------------------
// Function for fix border
function create_image_border($target, $newcopy) {

  list($w_orig, $h_orig) = @getimagesize($target);

  $img = "";
  $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));

  if ($ext == "gif") $img = imagecreatefromgif($target);
  elseif ($ext == "png") $img = imagecreatefrompng($target);
  elseif ($ext == "webp") $img = imagecreatefromwebp($target);
  else $img = imagecreatefromjpeg($target);

  $tci = imagecreatetruecolor($w_orig+6, $h_orig+6);
  if (exif_imagetype($target) == IMAGETYPE_PNG) imagealphablending($tci, false);

  $white = imagecolorallocate($tci, 255, 255, 255);

  // Draw a white rectangle
  imagefilledrectangle($tci, 1, 1, $w_orig+4, $h_orig+4, $white);

  // Copy over image
  imagecopy($tci, $img, 3, 3, 0, 0, $w_orig, $h_orig);

  if (exif_imagetype($target) == IMAGETYPE_PNG) imagesavealpha($tci, true);

  // Free memory
  imagedestroy($img);

  if ($ext == "gif") imagegif($tci, $newcopy);
  elseif ($ext == "png") imagepng($tci, $newcopy);
  elseif ($ext == "webp") imagewebp($tci, $newcopy);
  else imagejpeg($tci, $newcopy, 100);
}

function copy_image_border($target, $newcopy, $borderImage) {

  list($w_orig, $h_orig) = @getimagesize($target);

  $img = "";
  $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));

  if ($ext == "gif") $img = imagecreatefromgif($target);
  elseif ($ext == "png") $img = imagecreatefrompng($target);
  elseif ($ext == "webp") $img = imagecreatefromwebp($target);
  else $img = imagecreatefromjpeg($target);

  $tci = "";
  $extn = strtolower(pathinfo($borderImage, PATHINFO_EXTENSION));

  if ($extn == "gif") $tci = imagecreatefromgif($borderImage);
  elseif ($extn == "png") $tci = imagecreatefrompng($borderImage);
  elseif ($extn == "webp") $tci = imagecreatefromwebp($borderImage);
  else $tci = imagecreatefromjpeg($borderImage);

  // Copy over image
  imagecopy($tci, $img, 6, 6, 0, 0, $w_orig, $h_orig);

  // Free memory
  imagedestroy($img);

  if ($ext == "gif") imagegif($tci, $newcopy);
  elseif ($ext == "png") imagepng($tci, $newcopy);
  elseif ($ext == "webp") imagewebp($tci, $newcopy);
  else imagejpeg($tci, $newcopy, 100);
}
