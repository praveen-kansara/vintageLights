<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if(!defined('__URBAN_OFFICE__')) exit;

/*sa
 * Important note - Please use the function file where necessary. Place functions in the respective class.
 *
*/

# returns all thumbnails path
function get_all_thumbnails_path($path, $image_name) {

  global $image_size_dom, $site_url, $uploaded_image_path;

  $file_extension = '.'.pathinfo($image_name, PATHINFO_EXTENSION);
  $base_file_name = basename($image_name, ".".pathinfo($image_name, PATHINFO_EXTENSION));
  $thumb_images_path = [];
  foreach ($image_size_dom['thumbnails'] as $thumbnail_size) {
    $thumb_images_path[] = $site_url.$uploaded_image_path.$path.$base_file_name."-".$thumbnail_size.$file_extension;
  }
  return $thumb_images_path;

}

##update - check this
function get_site_url() {
  global $site_url;
  return $site_url;
}

# returns media path
function get_media_path() {
  global $image_size_dom, $site_url, $uploaded_image_path;
  return $site_url.$uploaded_image_path;
}

function get_contact_us_form() {

  $form = '<form id="contact_form">
             <input type="hidden" name="type" value="ajax" />
             <input type="hidden" name="tag" value="contact_us" />
             <input type="hidden" name="user_agent" value="'.$_SERVER['HTTP_USER_AGENT'].'" />
             <input type="hidden" name="ip" value="'.$_SERVER['REMOTE_ADDR'].'" />
             <div class="form-row">
                <div class="col-12 col-md-6 form-group"><label for="first-name">First Name<span class="red"><sup>*</sup></span></label><input type="text" class="form-control" id="first-name" name="first_name" required /></div>
                <div class="col-12 col-md-6 form-group"><label for="last-name">Last Name<span class="red"><sup>*</sup></span></label><input type="text" class="form-control" id="last-name" name="last_name" required /></div>
             </div>
             <div class="form-row">
                <div class="col-12 col-md-6 form-group"><label for="email">Email<span class="red"><sup>*</sup></span></label><input type="email" class="form-control" id="email" name="email" required /></div>
                <div class="col-12 col-md-6 form-group"><label for="phone">Phone<span class="red"><sup>*</sup></span></label><input type="text" class="form-control" id="phone-number" name="phone" required/></div>
             </div>
             <div class="form-row">
             <div class="col-12 form-group">
              <label for="email">Message<span class="red"><sup>*</sup></span></label>
              <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
              </div>
             </div>
             <button type="button" id="contact_submit" class="btn btn-black-border">Submit</button>
             <div class="form-group">
               <div class="form_row contact-form-success-msg mb-0" id="contact_message" hidden>
                <div class="alert alert-success alert-dismissable" >
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Message sent successfully.
                </div>
              </div>
              <div class="form_row contact-form-success-msg mb-0" id="contact_error_message" hidden>
                 <div class="alert alert-danger alert-dismissable" >
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><span id="inquiry_error_message"></span>
                 </div>
               </div>
              <div class="form_row mt-2 mb-0" id="contact_message_sending" hidden>
                <div class="alert alert-success alert-dismissable" >
                  Sending your request..
                </div>
              </div>
            </div>
          </form>';

  return $form;

}

## Update - Can these default settings come from config or any other file?
function get_default_product_featured_image() {
  global $site_url;
  return $site_url."static/front/images/not-available.jpg";
}

# Returns default banner image
function get_default_banner_image() {
  global $site_url;
  return $site_url."static/front/images/product-banner.jpg";
}

# Returns first $number words from string passed
function get_number_words($string, $number) {
  $words = explode(" ", $string, $number+1);
  $words[$number] = "";
  return implode(" ", $words);
}

##Update - Can these image related functions go to the image module class?

# Returns image absolute path using image name and path
function get_image_absolute_path($path, $image_name) {
  global $site_url, $uploaded_image_path;
  return $site_url.$uploaded_image_path.$path.$image_name;
}

# Returns global image path
function get_global_image_path($path, $image_name) {
  global $site_url, $uploaded_image_path;
  return $site_url.$uploaded_image_path.$path.$image_name;
}

# Returns all thumbnails global path
function get_all_global_thumbnails_path($path, $image_name) {

  global $image_size_dom, $site_url, $uploaded_image_path;

  $file_extension = '.'.pathinfo($image_name, PATHINFO_EXTENSION);
  $base_file_name = basename($image_name, ".".pathinfo($image_name, PATHINFO_EXTENSION));
  $thumb_images_path = [];
  foreach ($image_size_dom['thumbnails'] as $thumbnail_size) {
    $thumb_images_path[] = $site_url.$uploaded_image_path.$path.$base_file_name."-".$thumbnail_size.$file_extension;
  }
  return $thumb_images_path;

}

# Returns script tags
function build_script_tags($module_name) {
  global $js_references;
  $js = $js_references[$module_name];
  for($i=0; $i<count($js_references[$module_name]); $i++) {
    echo "<script src='".$js[$i]."?v=".get_date_mod($js[$i])."' type='text/javascript'></script>\n";
  }
}

# Prints array with pre tag
function print2($text, $exit='') {
  print '<pre>';
  print_r($text);
  print '</pre>';
  if($exit) exit;
}

# Returns the slug of given text
function slugify($text) {
  $text = strtolower($text);
  $text = preg_replace('/\s+/', '-', $text);
  $text = preg_replace('/[^\w\-]+/', '', $text);
  $text = preg_replace('/\-\-+/', '-', $text);
  $text = preg_replace('/^-+/', '', $text);
  $text = preg_replace('/-+$/', '', $text);
  return $text;
}

# Returns unique slug
function get_slug( $slug, $page_id = "", $counter = 0) {

  global $db;
  $main_slug = $slug;
  $slug = trim($slug);

  $invalid_chr = "'([\s\\\/\'\_\"\^\[\]\(\)\+\=\;\:\<\>\?\.\{\}\%\&\*\$\#\@\!\,\|])+'";
  $valid_chr = "-";

  $slug = preg_replace($invalid_chr, $valid_chr, $slug);
  if(substr($slug, -1) == '-') $slug = substr($slug, 0, -1);

  if($counter != 0) {
    $slug = $slug."-".$counter;
  }

  if($page_id=="") {
    $q = "select count(id) as cnt from page where slug = '".$slug."' limit 1";
  }
  else {
    $q = "select count(id) as cnt from page where slug = '".$slug."' and id <> '".$page_id."' limit 1";
  }

  $rs = $db->get_var($q);

  if($rs >= 1)  {
    $slug = get_slug($main_slug, $page_id , $counter+1);
  }

  return strtolower($slug);

}

# Returns the date in specified format
function display_date_format($dt, $show_time=false) {
  if($dt != '0000-00-00' && $dt != '0000-00-00 00:00:00') {
    $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dt);
    $newDateString = $show_time ? $myDateTime->format('d M Y') : $myDateTime->format('d M Y g:i A');
    return $newDateString;
  }
}

# Cookie Authentication
function CookieAuthenticate($urbanoffice_auth_key) {

  global $db;

  $qry = "SELECT pg.id, pg.name
            FROM  system_user as pg
            WHERE id = '$urbanoffice_auth_key'
            AND deleted   = 0
            AND is_active = 1
            LIMIT 1";

  $row = $db->get_row($qry);

  if($row) {
    $_SESSION['current_user_id']    = $row->id;
    $_SESSION['current_user_name']  = $row->name;
  }

  else {
    return false;
  }

}

# Building multiple tags
function build_tag_str($tag_val, $field, $value) {

  $tag_array = array();

  if($tag_val) $tag_array = explode(',', $tag_val);

  if($value == 1) { // Add tag
    if(in_array($field, $tag_array) == false) $tag_array[] = $field;
  } 
  else { // Remove tag
    if(($key = array_search($field, $tag_array)) !== false) unset($tag_array[$key]);
  }

  if(!empty($tag_array)) $tag_str = implode(',', $tag_array);
  else $tag_str = '';

  return $tag_str;

}

# Removes Cache
function remove_cache($uri, $clear_cache="") {
  
  global $cache_file_path;

  if($clear_cache == 1) {
    $files = glob("../".$cache_file_path.'*');
    foreach($files as $file) {
      if(is_file($file))
      unlink($file);
    }
  }

  if($uri) {
    $url = base64_encode($uri);
    $file_path = "../".$cache_file_path.$url.".htm";
    if(file_exists($file_path)) {
      unlink($file_path);
    }
  }

}

# Creates command using function (build_s3cmd) and uploads images on s3
function S3_image_handler($img_action, $path, $admin=false) {

  global $image_size_dom, $bucket_name, $uploaded_image_path, $s3_image_public_path;

    $local_original_image_path = $s3_image_public_path.$uploaded_image_path.$path;

  $is_moved = false;  

  # Copying original image to s3
  $S3_image_path = 's3://'.$bucket_name."/".$path;

  $exec_cmd = build_s3cmd('put', $local_original_image_path, $S3_image_path);
  
  exec($exec_cmd, $outputArray);
  
  if( $outputArray ) {
    $is_moved = true;
  }

  return $is_moved;

}

function create_md5_directories($file_name, $config_save_path){
  # Create the directory
  $md5 = @md5($file_name);

  $file_path = $config_save_path;

  for ($i = 0; $i < 6; $i++) {
    $file_path = $file_path.$md5[$i].'/';

    if (!is_dir($file_path)) $result = @mkdir($file_path, 0777);
  }
  return $file_path;
}

# -----------------------------------------------------------------
# Purpose: Common function for upload / delete files on S3
# Bucket name: [ defined in config ]
# @param: string $action - upload or delete
#         string $path - file path in md5 directory structure
# @return boolean
function S3_file_handler($action = null, $path = null) {
  global $bucketName, $report_root_path;

  if ($action && $path) {
    $local_file_path = $report_root_path.$path;
    $S3_file_path = 's3://'.$bucketName.'/'.$path;

    # ------------------
    # Delete mechanism
    if($action == 'delete') {
      if (exec(build_s3cmd('info', $S3_file_path))) {
        $output_array = array();
        $exec_cmd = build_s3cmd('del', $S3_file_path);

        exec($exec_cmd, $output_array);

        if($output_array) {
          if(is_file($local_file_path)) {
            unlink($local_file_path);
          }
          return true;
        }
      }
    }

    # ------------------
    # Upload mechanism
    if ($action == 'upload') {
      if (is_file($local_file_path)) {
        $output_array = array();

        $exec_cmd = build_s3cmd('put', $local_file_path, $S3_file_path);

        exec($exec_cmd, $output_array);

        if ($output_array) {
          unlink($local_file_path);
          return true;
        }
      }
    }

  }

  return false;
}

function build_s3cmd($method, $source, $destination='') {

  global $s3cfg;

  $mime_type = '';
  if ($method == 'put') {
    $method .= ' --acl-public';
    //$mime_type = '-m '.mime_content_type($source);
    $mime_type = '-m '.mime_type_info($source);
  }

  if ($method == 'cp') {
    $method .= ' --acl-public';
  }

  //return "s3cmd -c $s3cfg $mime_type $method --add-header 'Cache-control: max-age=31557600' $source $destination";

  return "s3cmd -c $s3cfg $mime_type --add-header='Cache-Control:max-age=31557600' $method $source $destination";

}

function mime_type_info($file) {
  // our list of mime types
  $mime_types = array(
    'txt' => 'text/plain',
    'htm' => 'text/html',
    'html' => 'text/html',
    'php' => 'text/html',
    'css' => 'text/css',
    'js' => 'application/javascript',
    'json' => 'application/json',
    'xml' => 'application/xml',

    // images
    'png' => 'image/png',
    'jpe' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'jpg' => 'image/jpeg',
    'gif' => 'image/gif',
    'bmp' => 'image/bmp',
    'ico' => 'image/vnd.microsoft.icon',
    'tiff' => 'image/tiff',
    'tif' => 'image/tiff',
    'svg' => 'image/svg+xml',
    'svgz' => 'image/svg+xml',
    'webp' => 'image/webp',

    // archives
    'zip' => 'application/zip',
    'rar' => 'application/x-rar-compressed',
    'exe' => 'application/x-msdownload',
    'msi' => 'application/x-msdownload',
    'cab' => 'application/vnd.ms-cab-compressed',

    // audio/video
    'mp3' => 'audio/mpeg',
    'qt' => 'video/quicktime',
    'mov' => 'video/quicktime',
    'flv'=>'video/x-flv',
    'mp4'=>'video/mp4',
    'f4v'=>'video/mp4',
    'swf' => 'application/x-shockwave-flash',
    'flv' => 'video/x-flv',

    // adobe
    'pdf' => 'application/pdf',
    'psd' => 'image/vnd.adobe.photoshop',
    'ai' => 'application/postscript',
    'eps' => 'application/postscript',
    'ps' => 'application/postscript',

    // ms office
    'doc' => 'application/msword',
    'docx' => 'application/msword',
    'rtf' => 'application/rtf',
    'xls' => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.ms-excel',
    'ppt' => 'application/vnd.ms-powerpoint',
    'pptx' => 'application/vnd.ms-powerpoint',
    'csv' => 'text/csv',

    // ms office
    'key' => 'application/octet-stream',
    'keynote' => 'application/x-iwork-keynote-sffkey',

    // open office
    'odt' => 'application/vnd.oasis.opendocument.text',
    'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
  );
  $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
  return $mime_types[$extension];
}

# This is our custom function to retrieve n level menus.
$cnt = 0;
function prepare_menu($menu_id, $parent_id = '', $menu_array = array()) {

  global $cnt, $db;
  $cnt++;

  $whr = "";

  if($parent_id) $whr = "and menu_item.parent_id = '$parent_id'";
  else $whr = "and menu_item.parent_id = ''";

  $sql = "SELECT menu_item.*,
                 IF(menu_item.type = 'custom', menu_item.url,page.uri ) AS menu_url,
                 page.slug
          FROM menu_item 
          LEFT JOIN page 
            ON page.id = menu_item.page_id
          WHERE menu_item.deleted = 0
            AND menu_item.menu_id = '{$menu_id}'
            {$whr}
          ORDER BY (menu_item.display_sequence * 1) ASC ";
  $rs = $db->get_results($sql);

  if($rs) {

    $menu_array = array();
    foreach($rs as $row) {
      $menu_array[$row->id] = (array)$row;
      if($row->id) $menu_array[$row->id]['child'] = prepare_menu($menu_id, $row->id, $menu_array);

    }
  } else {
    return;
  }

  return $menu_array;
}

$menu_item_counter = 1;

# Returns menu item template in menu module
function get_menu_item_template($menu_item) {

  global $menu_item_counter;

  $page_meta_obj = new Meta();
  $page_meta_array =  $page_meta_obj->get_all_page_meta($menu_item['id']);

  $menu_item_css = isset($page_meta_array['menu_item_css']) ? $page_meta_array['menu_item_css'] : "";
  $menu_item_target = isset($page_meta_array['menu_item_target']) ? $page_meta_array['menu_item_target'] : "";
  $is_checked = $menu_item_target!="" ? "checked" : "";

  if($menu_item['type'] == "custom") {
    $menu_item_string = <<<EOQ
      <li data-menu-id="$menu_item[id]" data-page-id="$menu_item[page_id]" id="menu_$menu_item_counter">
        <div class="menuDiv">
          <div class="menu-title">
            <span>
              <span class="itemTitle">$menu_item[name]</span>
              <span data-id="" class="expand-edit ui-icon ui-icon-triangle-1-s"><span></span></span>
            </span>
          </div>
          <div id="menuEdit" class="menuEdit hidden">
            <div class="">
              <form role="form">
                <div class="box-body">
                  <div class="form-group">
                    <label>Navigation Label</label>
                    <input type="text" class="form-control navigation-label input-sm" value="$menu_item[name]" 
                    placeholder="Enter navigation label" />
                  </div>
                  <div class="form-group">
                    <label>URL</label>
                    <input type="text" class="form-control page-url input-sm" value="$menu_item[url]" 
                    placeholder="Enter url" />
                  </div>
                  <div class="form-group">
                    <label class="mt-checkbox mt-checkbox-outline">
                      <input $is_checked value="_blank" type="checkbox" class="open-new-tab"> Open link in new tab<span></span>
                    </label>
                  </div>
                  <div class="form-group">
                    <label>Css Class (optional)</label>
                    <input type="text" class="form-control css-class input-sm" value="$menu_item_css" />
                  </div>
                  <div>
                    <a href="#" class="remove-menu">Remove</a>
                    <input type="hidden" class="page-type" value="custom"/>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
EOQ;
  }
  else {
    $menu_item_string = <<<EOQ
      <li data-menu-id="$menu_item[id]" data-page-id="$menu_item[page_id]" id="menu_$menu_item_counter">
        <div class="menuDiv">
          <div class="menu-title">
            <span>
              <span class="itemTitle">$menu_item[name]</span>
              <span data-id="" class="expand-edit ui-icon ui-icon-triangle-1-s"><span></span></span>
            </span>
          </div>
          <div id="menuEdit" class="menuEdit hidden">
            <div class="">
              <form role="form">
                <div class="box-body">
                  <div class="form-group">
                    <label>Navigation Label</label>
                    <input type="text" class="form-control navigation-label input-sm" value="$menu_item[name]" 
                      placeholder="Enter navigation label">
                  </div>
                  <div class="form-group">
                    <label class="mt-checkbox mt-checkbox-outline">
                      <input $is_checked value="_blank" type="checkbox" class="open-new-tab"> Open link in new tab
                      <span></span>
                    </label>
                  </div>
                  <div class="form-group">
                    <label>Css Class (optional)</label>
                    <input type="text" class="form-control css-class input-sm" value="$menu_item_css" />
                  </div>
                  <div>
                    <a href="#" class="remove-menu">Remove</a>
                    <input type="hidden" class="page-type" value="page"/>
                    <input type="hidden" class="page-url" value=""/>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
EOQ;
  }


  $menu_item_counter++;

  return $menu_item_string;
  
}

function get_menu_child_admin ( $menu_item ) {

  $menu_string = "";

  global $cnt, $current_class, $current_full_url;
  $cnt++;

  if($cnt > 100) {
    die;
    //break;
  }

  if(isset($menu_item['child']) && count($menu_item['child']) > 0) {

    $menu_item['uri'] = isset($menu_item['uri']) ? $menu_item['uri'] : "";

    $menu_string .= '<ol>';
    foreach ($menu_item['child'] as $sub_menu) {
      $menu_string .= get_menu_item_template($sub_menu);
      $menu_string .= get_menu_child_admin($sub_menu);
    }
    $menu_string .= '</ol>';
    $menu_string .= '</li>';
    
  }
  else {
    $menu_string .= '</li>';
  }

  return $menu_string;

}

# Returns the specified thumbnail of the given image
function get_image_thumbnail($path, $image_name, $thumbnail_size) {

  global $site_url, $uploaded_image_path;

  $file_extension = '.'.pathinfo($image_name, PATHINFO_EXTENSION);
  $base_file_name = basename($image_name, ".".pathinfo($image_name, PATHINFO_EXTENSION));

  return $path.$base_file_name."-".$thumbnail_size.$file_extension;

}

# Crop the image from center and save in the same directory with the given name
function crop_image_from_center($max_width, $max_height, $extn, $orig_img, $cropped_image, $imgPath) {

  $main_img_file = $imgPath.$orig_img;

  $img_cropped_file = $imgPath.$cropped_image;

  switch($extn) {

    case ".gif" : 
    $mainImage = imagecreatefromgif($main_img_file) or die('Problem In opening Source Image');
    break;

    case ".jpg" : 
    $mainImage = imagecreatefromjpeg($main_img_file) or die('Problem In opening Source Image');
    break;

    case ".jpeg" : 
    $mainImage = imagecreatefromjpeg($main_img_file) or die('Problem In opening Source Image');
    break;

    case ".png" :
    $mainImage = imagecreatefrompng($main_img_file) or die('Problem In opening Source Image');
    break;

    case ".webp" :
    $mainImage = imagecreatefromwebp($main_img_file) or die('Problem In opening Source Image');
    break;

  }
  
  $mainWidth = imagesx($mainImage);
  $mainHeight = imagesy($mainImage);

  $ratio_orig = $mainWidth/$mainHeight;

  if ($max_width/$max_height > $ratio_orig) {
    $new_height = $max_width/$ratio_orig;
    $new_width = $max_width;
  } 
  else {
    $new_width = $max_height*$ratio_orig;
    $new_height = $max_height;
  }

  $x_mid = $new_width/2;  //horizontal middle
  $y_mid = $new_height/2; //vertical middle

  $process = imagecreatetruecolor(round($new_width,0), round($new_height,0));
  
  // Added on 23 Sept 2014 to fix the transparent image issue (background was getting black).
  if (exif_imagetype($main_img_file) == IMAGETYPE_PNG) {
    imagealphablending($process, FALSE);
    imagesavealpha($process, TRUE);
  }
  imagecopyresampled($process, $mainImage, 0, 0, 0, 0, $new_width, $new_height, $mainWidth, $mainHeight);
  
  $mycropped_img = imagecreatetruecolor($max_width, $max_height);
  
  // Added on 23 Sept 2014 to fix the transparent image issue (background was getting black).
  if (exif_imagetype($main_img_file) == IMAGETYPE_PNG) {
    imagealphablending($mycropped_img, FALSE);
    imagesavealpha($mycropped_img, TRUE);
  }
  imagecopyresampled($mycropped_img, $process, 0, 0, ($x_mid-($max_width/2)), ($y_mid-($max_height/2)), $max_width, $max_height, $max_width, $max_height);
  
  switch($extn) {

    case ".gif" :
    imagegif($mycropped_img, $img_cropped_file) or die('Problem In Saving');
    break;

    case ".jpg" :
    imagejpeg($mycropped_img, $img_cropped_file) or die('Problem In Saving');
    break;

    case ".jpeg" :
    imagejpeg($mycropped_img, $img_cropped_file) or die('Problem In Saving');
    break;

    case ".png" :
    imagepng($mycropped_img, $img_cropped_file) or die('Problem In Saving');
    break;

    case ".webp" :
    imagewebp($mycropped_img, $img_cropped_file) or die('Problem In Saving');
    break;

  }

  imagedestroy($process);
  imagedestroy($mainImage);
}

# Returns the modified time of the given file
function get_date_mod($filename) {
  if (file_exists($filename)) {
    return date ("Ymdhis", filemtime($filename));
  } 
  else {
    return 1;
  }
}

# Genrate sitemap page on content submit
function curl_request() {

  global $sitemap_ping;

  /*if($_SERVER['SERVER_NAME'] == 'urbanofficebybe.com') {

    foreach ($sitemap_ping as $url) {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $data = curl_exec($ch);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
     }
  }*/

}

# TODO update smtp credentials
function send_email($to_array, $from, $replyto, $subject, $body, $is_html=true, $bcc_array=null, $attachment=null) {

  global $log, $smtp_host, $smtp_port, $smtp_username, $smtp_password;

  require_once("include/PHPMailer.php");
  require_once("include/Exception.php");
  require_once("include/SMTP.php");
  $mail = new PHPMailer();

  $mail->SMTPDebug    = 0;
  $mail->Mailer       = 'smtp';
  $mail->Host         = $smtp_host;
  $mail->Port         = $smtp_port;
  $mail->SMTPSecure   = 'starttls';
  $mail->SMTPAuth     = true;

  $mail->Username = $smtp_username;
  $mail->Password = $smtp_password;

  if ($is_html) $mail->IsHTML();

  $mail->setFrom($from['email'], $from['name'], 0); 

  if($replyto) $mail->addReplyTo($replyto['email'], $replyto['name']);

  // Condition to check multiple recipient or single
  if (count($to_array) == count($to_array, 1)) {
    $mail->addAddress($to_array['email'], $to_array['name']); 
  }
  else {
    if($to_array) {
      foreach( $to_array as $to ) {
        $mail->addAddress($to['email'], $to['name']);
      }
    }
  }

  if($bcc_array) {
    foreach( $bcc_array as $bcc ) {
      $mail->addBCC($bcc['email'], $bcc['name']);
    }
  }

  $mail->Subject = $subject;

  $mail->Body = $body;

  if($attachment) $mail->addAttachment($attachment);

  if($mail->send()) {
    return 1;
  } else {
    $error_message = $mail->ErrorInfo;
    $log->fatal("Error Notify By Email: subject: $subject body $body ErrorInfo: $error_message");
    return 0;
  }
}

function create_rdir_json() {

  global $rdir_json;
  $store_uri = [];
  $json_file = "../".$rdir_json;

  require_once("include/classes/Redir.php");
  $objRedir = new Redir();
  $get_rdir_urls = $objRedir->get_list_on_site('deleted = 0');

  if($get_rdir_urls['list']) {

    foreach($get_rdir_urls['list'] as $seed) {
      $row = $seed->get_list_view_data();
      $assign_data['id'] = $row['id'];
      $assign_data['uri'] = $row['uri'];
      $assign_data['redir'] = $row['redir'];
      $assign_data['redir_type'] = $row['redir_type'];
      $assign_data['count'] = $row['count'];
      array_push($store_uri, $assign_data);

    }
    $json_uri  =  json_encode($store_uri);

    if(is_file($json_file)) unlink($json_file);

    $handle = fopen($json_file, "w");

    fwrite($handle, $json_uri);

    fclose($handle);
  
  } else {

    if(is_file($json_file)) unlink($json_file);
  }

}

function count_url_hit($rdir_id) {

  if(!empty($rdir_id)) {
    require_once("include/classes/Redir.php");
    $objRedir = new Redir();
    
    $count_url = $objRedir->retrieve($rdir_id)->count;
    $count_url = ($count_url + 1);
    $objRedir->id = $rdir_id;
    $objRedir->count = $count_url;
    $objRedir->save();  
  }

}

////// Minify the HTML content ////////
function sanitize_output($buffer) {

  $search = array(
      '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
      '/[^\S ]+\</s',     // strip whitespaces before tags, except space
      '/(\s)+/s',         // shorten multiple whitespace sequences
      '/<!--(.|\s)*?-->/' // Remove HTML comments
  );
  $replace = array(
      '>',
      '<',
      '\\1',
      ''
  );

  $buffer = preg_replace($search, $replace, $buffer);
  return $buffer;
}

# Get short code content
function parse_short_code($short_code = '', $type ='') {
  $widget_data = '';
  if(!empty($short_code)) {

    require_once("include/classes/Page.php");
    $objPage = new Page();

    $widget_data = $objPage->parse_short_code($short_code, $type);

  }

  return $widget_data;

}

# Rename image with crop size.
# $img_atr for thumbnail or crop size
function createImageWithSize($source = '',$img_size='') {

  $img_url = '';
  global $media_path_s3, $media_path_site;

  if(!empty($source) && !empty($source['path']) && !empty($source['name'])) {
    $img_path = ($source['at_s3'] == 1) ? $media_path_s3 : $media_path_site;
    $img_url = $img_path.$source['path'].$source['name'];
    $path_info = pathinfo($img_url);
    if(!empty($path_info)) {
      $img_url =  $path_info['dirname'].'/'.$path_info['filename'].'-'.$img_size.'.'.$path_info['extension'];
    }
  }
  return $img_url;
}

# Get the image size from the site url
function createSiteImageWithSize($source = '',$img_size='') {

  $img_url = '';
  global $media_path_s3, $media_path_site;

  if(!empty($source) && !empty($source['path']) && !empty($source['name'])) {
    $img_url = $media_path_site.$source['path'].$source['name'];
    $path_info = pathinfo($img_url);
    if(!empty($path_info)) {
      $img_url =  $path_info['dirname'].'/'.$path_info['filename'].'-'.$img_size.'.'.$path_info['extension'];
    }
  }
  return $img_url;
}

function check_post_type_by_uri($current_full_url) {
  $type_res = '';
  if(!empty($current_full_url)) {
    require_once("include/classes/Page.php");
    $Page = new Page();
    $type_res = $Page->check_post_type_by_uri($current_full_url);
  }
  return $type_res;
}

function check_uri_by_slash($current_full_url) {
  global $db;

  if(substr($current_full_url, -1) == "/") {
    return false;
  }

  $uri_with_slash = $current_full_url."/";

  $q = "SELECT 'page' AS type 
        FROM page
        WHERE (uri = '$uri_with_slash')";

  $uri = $db->get_row($q);
  
  if($uri && $uri->type) return true;
  else return false;
  
}

function prepare_email_html_body($mail_body = "") {
  require_once("include/email_template.php");

  $email_body = $header_content;
  if(!empty($mail_body)) {
    $email_body.= $mail_body;
  }
  $email_body .= $footer_content;
  return $email_body;
}


function property_slider() {
  global $front_img_path;
  $slider_content  ='';
  /*$slider_content =  '
    <div class="slider-section">
      <div class="slider multiple-items">
      <div class="slider-box"><img data-image-name="53-west-slider.jpg" src="http://urbanoffice.local/media/a/5/9/e/f/b/53-west-slider.jpg" alt="53 West" title="53 West" width="550" />
      <h5>53 West, Houston</h5>
      <a href="#" class="btn learn-more-btn">Learn More</a></div>
      <div class="slider-box"><img data-image-name="spring-branch-village-slider-2.jpg" src="http://urbanoffice.local/media/e/7/7/1/6/9/spring-branch-village-slider-2.jpg" alt="" title="Spring Branch Village" width="550" />
      <h5>Spring Branch Village, Houston</h5>
      <a href="#" class="btn learn-more-btn">Learn More</a></div>
      <div class="slider-box"><img data-image-name="53-west-slider.jpg" src="http://urbanoffice.local/media/a/5/9/e/f/b/53-west-slider.jpg" alt="53 West" title="53 West" width="550" />
      <h5>53 West11, Houston</h5>
      <a href="#" class="btn learn-more-btn">Learn More</a></div>
      <div class="slider-box"><img data-image-name="spring-branch-village-slider-2.jpg" src="http://urbanoffice.local/media/e/7/7/1/6/9/spring-branch-village-slider-2.jpg" alt="" title="Spring Branch Village" width="550" />
      <h5>Spring Branch Village11, Houston</h5>
      <a href="#" class="btn learn-more-btn">Learn More</a></div>
      </div>
    </div>';*/

  return $slider_content;

}


function smart_amenities_slider() {
  global $local_cdn_image, $amenities_dom;
  
  $content = '<div class="amenities-section text-center">
    <div class="container">
      <div class="row row-eq-height">';
  
  foreach ($amenities_dom as $key => $amenity_detail) {
    if($amenity_detail['show_on_page_section'] == 1) {
      
      if(isset($amenity_detail['subtitle']) && !empty($amenity_detail['subtitle'])) $amentities_subtitle = '<p class="subtitle">'.$amenity_detail['subtitle'].'</p>';
      else $amentities_subtitle = '';
      
      $content.='<div class="col-12 col-md-4 col-lg-3 row-box">
          <div class="amenities-box">
            <div class="content-section">
              <div class="image-box">
                <img src="'.$local_cdn_image.$amenity_detail['image_name'].'" width="'.$amenity_detail['width'].'" height="'.$amenity_detail['height'].'" alt="'.$amenity_detail['title'].'" title="'.$amenity_detail['title'].'">
              </div>
              <p>'.$amenity_detail['title'].'</p>
              '.$amentities_subtitle.'
            </div>
          </div>
        </div>';   
    }
  }
      
  $content.='</div></div></div>';
  return $content;    
}

function footer_location_section() {
  global $local_cdn_image, $site_url, $front_img_path;

  $content =  '
    <div class="row">
       <div class="col-12 col-md-6 pr-0">
          <div class="location-box"><a href="'.$site_url.'houston/"><img data-image-name="houston-location.jpg" src="'.$local_cdn_image.'houston-location.jpg" alt="Houston Location" title="Houston Location" width="1170" height="700" /></a><a href="'.$site_url.'houston/" class="btn location-btn"> <span class="location-ico">Houston</span></a></div>
       </div>
       <div class="col-12 col-md-6 pl-0">
          <div class="location-box"><img src="'.$local_cdn_image.'houston-map.jpg" alt="Houston Map" title="Houston Map" width="800" height="479" /></div>
       </div>
    </div>
    <div class="row mt-25">
       <div class="col-12 col-md-6 pr-0 box-order-2">
          <div class="location-box"><img src="'.$local_cdn_image.'san-antonio-map.jpg" alt="San Antonio Map" title="San Antonio Map" width="800" height="479" /></div>
       </div>
       <div class="col-12 col-md-6 pl-0 box-order-1">
          <div class="location-box"><a href="'.$site_url.'san-antonio/"><img data-image-name="san-antonio-location.jpg" src="'.$local_cdn_image.'san-antonio-location.jpg" alt="San Antonio Location" title="San Antonio Location" width="1170" height="700" /></a><a href="'.$site_url.'san-antonio/" class="btn location-btn"> <span class="location-ico">San Antonio</span></a></div>
       </div>
              <div class="col-12 col-md-6 pr-0">
          <div class="location-box"><a href="'.$site_url.'austin/"><img data-image-name="houston-location.jpg" src="'.$local_cdn_image.'Austin-location.jpg" alt="Houston Location" title="Austin Location" width="1170" height="700" /></a><a href="'.$site_url.'austin/" class="btn location-btn"> <span class="location-ico">Austin</span></a></div>
       </div>
       <div class="col-12 col-md-6 pl-0">
          <div class="location-box"><img src="'.$local_cdn_image.'austin-map.jpg" alt="Austin Map" title="Austin Map" width="800" height="479" /></div>
       </div>
    </div>
    </div>';

  return $content;

}

#---------------Set the schema - START------------

# function to generate custom schemas
function generate_custom_schema($custom_post_type = 'page', $current_full_url = '', $flag = '') {

  /*include_once('aw-custom-schema.php');

  $schema_output = load_custom_schemas($current_full_url,$custom_post_type, $flag);

  return $schema_output;*/

}

#---------------Set the schema - END------------
 
// Function to get the Authenticated file URL
function getFileURL($bucketName,$filename) {

  include_once("S3.php");

  global $aws_access_key;
  global $aws_secret_key;

  // calculates an image URL for display on a view/page
  $return = '';
  $s3 = new S3($aws_access_key, $aws_secret_key);
  $timestamp=3600;
  $return = $s3->getAuthenticatedURL($bucketName, $filename, $timestamp);

  return $return;
}

function create_breadcrumb() {
  global $current_full_url, $site_url;

  $breadcrumb_str = '';

  $final_array = array();
  if(!empty($current_full_url)) {

    $new_str = str_replace($site_url,'', $current_full_url);
    $breadcrumb_array = array_filter(explode("/", $new_str));

    if(!empty($breadcrumb_array)) {
      $temp_str = '';
      $final_array[] = array(
        'title'=> '',
        'uri'  => '',
      );
      foreach ($breadcrumb_array as $bval) {
        $temp_str .= $bval."/";

        $title_array = explode("-", $bval);
        $title_str = implode(" ", $title_array);

        $final_array[] = array(
          'title'=> ucwords($title_str),
          'uri'  => $temp_str,
        );
      }
    }
  }

  if(!empty($final_array)) {
    $breadcrumb_li = '';
    $itr_count = count($final_array) < 1 ? count($final_array) : count($final_array)-1;

    $b_cnt = 0;
    foreach ($final_array as $k => $v) {

      if($b_cnt == 0) {
        $breadcrumb_title = "Home";
      } else $breadcrumb_title = $v['title'];

      $active_class = $aria_current_attr = '';

      if($k == $itr_count) {
        $active_class = 'active';
        $aria_current_attr = 'aria-current="page"';
      }

      $breadcrumb_li .= '<li class="breadcrumb-item '.$active_class.'"'.$aria_current_attr.'><a href="'.$site_url.$v['uri'].'">'.$breadcrumb_title.'</a></li>';
    $b_cnt++;
    }
  }

  if(!empty($current_full_url)) {
    $breadcrumb_str = '<nav aria-label="breadcrumb">
                       <ol class="breadcrumb">'.$breadcrumb_li.'</ol>
                      </nav>';
  }

  return $breadcrumb_str;

}

function get_featured_properties() {
  global $location_dom, $media_path_site, $media_path_s3;

  $obj_page      = new Page();
  $property_list = $obj_page->get_all_properties(100, "", array("featured_image"));

  $featured_property_block = '';

  if(!empty($property_list)) {

    foreach ($property_list as $key => $property_details) {
      $property_name_arr   = array();
      $property_name_arr[] = $property_details['name'];

      if(!empty($property_details['location'])) $property_name_arr[] = $location_dom[$property_details['location']];

      $image_path = "";

      if(!empty($property_details['icon'])) {
        if($property_details['icon']['at_s3'] == 1) {
          $image_path = $media_path_s3.$property_details['icon']['path'].$property_details['icon']['name'];
        } else {
          $image_path= $media_path_site.$property_details['icon']['path'].$property_details['icon']['name'];
        }
      }

      $featured_property_block.= '<div class="col-12 col-md-4">
            <div class="property-card">
               <div class="img-box"><a href="'.$property_details['uri'].'"><img src="'.$image_path.'" alt="'.$property_details['name'].'" title="'.$property_details['name'].'" width="398" height="558" /></a></div>
               <h3><a href="'.$property_details['uri'].'">'.strtoupper(implode(', ', $property_name_arr)).'</a><a></a></h3>
               <a href="'.$property_details['uri'].'" class="btn learn-more-btn">Learn More</a>
            </div>
         </div>';
    }
  }

  return $featured_property_block;

}

function load_faq() {
  require_once("include/classes/Page.php");
  $obj_page = new Page();

  $faq_details = $obj_page->get_all_faq(10);

  $content = '';
  $faq_data = array();

  if(!empty($faq_details)) {
    $content .= '<div id="accordion">';

    $count = 0;
    $open_class = 'aria-expanded="true"';
    $answer_show_class = 'show';
    foreach ($faq_details as $key => $faq_detail) {
      $content.='<div class="card">
                  <div id="headingOne" class="card-header">
                    <h5 class="mb-0"><button class="btn btn-link" data-toggle="collapse" data-target="#collapse'.$count.'" '.$open_class.' aria-controls="collapseOne"><br />'.$faq_detail['title'].'<br />
                    </button></h5>
                  </div>
                  <div id="collapse'.$count.'" class="collapse '.$answer_show_class.'" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">'.$faq_detail['content'].'</div>
                  </div>
                </div>';

      $count++;
      $data['question'] = $faq_detail['title'];
      $data['answer']   = strip_tags($faq_detail['content']);
      $faq_data[] = $data;

      $answer_show_class = $open_class = '';
    }

    $content.='</div>'.
      '<script type="application/ld+json">' . 
      convertToJSONLD($faq_data) . 
      '</script>';
  }

  return $content;
}

function convertToJSONLD($faqData) {
  $jsonLd = array(
      '@context' => 'https://schema.org',
      '@type' => 'FAQPage',
      'mainEntity' => array()
  );

  foreach ($faqData as $item) {
      $jsonLd['mainEntity'][] = array(
          '@type' => 'Question',
          'name' => $item['question'],
          'acceptedAnswer' => array(
              '@type' => 'Answer',
              'text' => $item['answer'],
          ),
      );
  }

  return json_encode($jsonLd, JSON_PRETTY_PRINT);
}

function calcualte_center_lat_lng($coordinate = array()) {
  $final_cord_array = array();
  $count_array      = array();

  $center_latitude = $center_longitude = '';

  // Remove the markers which are away from max crowd area
  // custom algorithm
  if (count($coordinate) > 1) {
    foreach ($coordinate as $each_cord) {
      $short_lat = substr(str_replace("-", "", $each_cord[0]), 0, 2);
      $short_lon = substr(str_replace("-", "", $each_cord[1]), 0, 2);

      if (isset($count_array[$short_lat.$short_lon])) {
        $count_array[$short_lat.$short_lon] = $count_array[$short_lat.$short_lon]+1;
      } else $count_array[$short_lat.$short_lon] = 1;
    }

    foreach ($coordinate as $each_cord) {
      $short_lat = substr(str_replace("-", "", $each_cord[0]), 0, 2);
      $short_lon = substr(str_replace("-", "", $each_cord[1]), 0, 2);

      if ($count_array[$short_lat.$short_lon] > count($coordinate)/3) {
        $final_cord_array[] = $each_cord;
      }
    }

  } else {
    $final_cord_array = $coordinate;
  }

  $map_center_coordinates = array();
  if (!empty($final_cord_array)) $map_center_coordinates = GetCenterFromDegrees($final_cord_array);

  if (!empty($map_center_coordinates)) {
    $center_latitude  = $map_center_coordinates[0];
    $center_longitude = $map_center_coordinates[1];
  } else {
    $center_latitude  = $coordinate[0]  ? $coordinate[0] : '';
    $center_longitude = $coordinate[1]  ? $coordinate[1] : '';
  }

  return array('center_latitude' => $center_latitude, 'center_longitude' => $center_longitude);
}


function GetCenterFromDegrees($data) {
  if (!is_array($data)) return FALSE;
  $num_coords = count($data);
  $X = 0.0;
  $Y = 0.0;
  $Z = 0.0;
  foreach ($data as $coord) {
      $lat = $coord[0] * pi() / 180;
      $lon = $coord[1] * pi() / 180;
      $a = cos($lat) * cos($lon);
      $b = cos($lat) * sin($lon);
      $c = sin($lat);
      $X += $a;
      $Y += $b;
      $Z += $c;
  }
  $X /= $num_coords;
  $Y /= $num_coords;
  $Z /= $num_coords;
  $lon = atan2($Y, $X);
  $hyp = sqrt($X * $X + $Y * $Y);
  $lat = atan2($Z, $hyp);
  return array($lat * 180 / pi(), $lon * 180 / pi());
}

function create_breadcrumb_for_property($page_location = '') {
  global $current_full_url, $site_url, $db, $location_dom;

  $breadcrumb_str = '';

  $final_array = array();

    if(!empty($current_full_url)) {

      $sql = "SELECT id, title AS name, uri, slug
              FROM page
              WHERE post_type IN ('page')
                AND status = 'published'
                AND location = '$page_location '
                AND deleted = 0
              LIMIT 1";

      $page_detail = $db->get_row($sql);

      if(!empty($page_detail)) {
        $location_uri = $page_detail->slug . '/';  // added trailing slash | SR Jun 24, 2023
      } else {
        $location_uri = '';
      }

      $new_str = str_replace($site_url,'', $current_full_url);
      $breadcrumb_array = array_filter(explode("/", $new_str));
      $breadcrumb_array = array_reverse($breadcrumb_array);
      array_pop($breadcrumb_array);

      if(!empty($breadcrumb_array)) {
        $temp_str = '';
        $final_array[] = array(
          'title'=> '',
          'uri'  => '',
        );

        // $final_array[] = array(
        //   'title'=> 'Location',
        //   'uri'  => 'javascript:void();',
        // );

        $final_array[] = array(
          'title'=> $location_dom[$page_location],
          'uri'  => $location_uri,
        );
        foreach ($breadcrumb_array as $bval) {
          $temp_str .= $bval."/";

          $title_array = explode("-", $bval);
          $title_str = implode(" ", $title_array);

          $final_array[] = array(
            'title'=> ucwords($title_str),
            'uri'  => $temp_str,
          );
        }
      }
    }

  if(!empty($final_array)) {
    $breadcrumb_li = '';
    $itr_count = count($final_array) < 1 ? count($final_array) : count($final_array)-1;

    $b_cnt = 0;
    foreach ($final_array as $k => $v) {

      if($b_cnt == 0) {
        $breadcrumb_title = "Home";
      } else $breadcrumb_title = $v['title'];

      $active_class = $aria_current_attr = '';

      if($k == $itr_count) {
        $active_class = 'active';
        $aria_current_attr = 'aria-current="page"';
        $v['uri'] = 'property/' . $v['uri']; // prefixed with 'property/' to suppress 404 | SR 24 Jun 2023
      }

      $breadcrumb_li .= '<li class="breadcrumb-item '.$active_class.'"'.$aria_current_attr.'><a href="'.$site_url.$v['uri'].'">'.$breadcrumb_title.'</a></li>';
    $b_cnt++;
    }
  }

  if(!empty($current_full_url)) {
    $breadcrumb_str = '<nav aria-label="breadcrumb">
                       <ol class="breadcrumb">'.$breadcrumb_li.'</ol>
                      </nav>';
  }

  return $breadcrumb_str;
}

#Check Email Validity Using quickemailverification.com API
function check_email_validity($email) {
  global $quickemailverification_api_key;

  $ch = curl_init();
  
  $headers = array(
    'Accept: application/json',
    'Content-Type: application/json',
  );

  $response_headers = array();

  curl_setopt($ch, CURLOPT_URL, 'http://api.quickemailverification.com/v1/verify?email='.$email.'&apikey='.$quickemailverification_api_key);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
  curl_setopt($ch, CURLOPT_HEADERFUNCTION,
    function($curl, $header) use (&$response_headers)
    {
      $len = strlen($header);
      $header = explode(':', $header, 2);
      if (count($header) < 2) // ignore invalid headers
        return $len;

      $response_headers[strtolower(trim($header[0]))][] = trim($header[1]);

      return $len;
    }
  );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  // Timeout in seconds
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);

  $email_validation_data = curl_exec($ch);
  $email_validation_array = json_decode($email_validation_data,1);

  $remaining_daily_credit_cnt = isset($response_headers['x-qev-remaining-credits'][0]) ? $response_headers['x-qev-remaining-credits'][0] : 0; 

  if(!empty($email_validation_array) || $remaining_daily_credit_cnt != 0) {
    if($email_validation_array['result'] == 'valid') {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

function media_pre_processor_new_2($content) {

  require_once("classes/Media.php");
  require_once("include/simple_html_dom.php");

  // Removed these vars as the default path to s3 as the image is uploaded directly
  // at the time of image upload
  //global $media_path_site, $media_path_s3;

  $media_obj = new Media();

  //$content = str_replace("data-image-name", "data_image_name", $content);

  $html = str_get_html($content);

  if($html == "") return $content;

  // foreach($html->find('img') as $tag) {

  //   $image_details = $media_obj->get_media_by_name($tag->data_image_name);

  //   if($image_details) {
  //     if($image_details->at_s3 == 1) {
  //       $src = $tag->src;
  //       $new_path = str_replace($media_path_site, $media_path_s3, $src);
  //       $content = str_replace($src, $new_path, $content);
  //     }
  //   }

  // }

  $content = str_replace("data_image_name", "data-image-name", $content);

  return $content;

}