<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

ini_set('display_errors',0);
error_reporting(0);
/**
 * The env file is either in the parent or in the grand parent directory
 * removing the absolute path as it might fail cli runs
 * 
 */
// switch ($_SERVER["HTTP_HOST"]) {
// 	case 'urbanofficetx.com':
// 		me/urbantx/.env');
// 		break;
	
// 	case 'staging.urbanofficetx.com':
// 		me/urbantx/staging.urbanofficetx.com/.env');
// 		break;
	
// 	default:
// }


// if (file_exists('../.env')) {
// 	.env');
// } elseif (file_exists('../../.env')) {
// 		../.env');
// } else {
// 		// Handle the case when neither file is found, if needed
// 		die('.env file not found.');
// }

$conn["server"]   =  "vintage.local";
$conn["dbname"]   = "urbantx_db";
$conn["username"] = "root";
$conn["password"] = "mysql";
$conn["encoding"] = "utf8mb4"; // newly added for new connection


// $conn["server"]   = "vintage.local";
// $conn["dbname"]   = "urbantx_db";
// $conn["username"] = "urbantx_dbuser";
// $conn["password"] = "g[emGjDlFTH+";
// $conn["encoding"] = "utf8mb4"; // newly added for new connection

require_once("include/Mobile_Detect.php");
$detect = new Mobile_Detect();

if($detect->isMobile()) {
	$max_entries_per_page  = 20;
	$max_entries_per_panel = 20;
}
else { 
	$max_entries_per_page  = 50;
	$max_entries_per_panel = 50;
}

$max_blogs_per_page = 18;
$max_entries_per_page = 20;
$max_entries_media_panel = 50;

$image_extn_dom = array('.jpeg', '.jpg', '.png', '.svg', '.webp');

#commons path
$uploaded_image_path = 'media/';

$site_name = "UrbanOfficeTX";

$site_url =  "http://vintage.local/";
$domain_name = "http://vintage.local";
$site_url =  "http://vintage.local/";

$local_cdn = "http://vintage.local/";
$local_cdn_image = "https://d34hpiwagsd2go.cloudfront.net/static/front/images/";

$front_static_files_path = "/home/urbantx/public_html/static/front/";
$front_img_path = $site_url."static/front/images/";

$media_path_site = $site_url."media/";
$media_path_s3   = "https://d34hpiwagsd2go.cloudfront.net/";

$cache_file_path = "../awecm/cache/";
$rdir_json       = "../awecm/rdir/rdir.json";
$property_path   = "property/";
$press_path      = "press/";
$blog_path       = "blog/";
$our_team_path   = "our-team/";
$s3_image_public_path = '/home/urbantx/public_html/';

$upload_path = '/home/urbantx/public_html/';

$s3cfg = '/home/urbantx/.s3cfg';
$bucket_name = 'com-urbanofficetx-cdn';

/* Newly Added Connection */
require_once( $app_path . '/include/ez_sql_core.php');
require_once( $app_path . '/include/ez_sql_mysqli.php');

require_once('include/dbconnect.inc.php');

#define('NON_PUBLIC_CRON_PATH', '/home/urbantx/awecm');
#define('MEDIA_PATH',           '/home/urbantx/public_html/');

define('NON_PUBLIC_CRON_PATH', $env["NON_PUBLIC_CRON_PATH"]);
define('MEDIA_PATH',           $env["MEDIA_PATH"]);      
$smtp_host     = 'smtp.office365.com';
$smtp_port     = "587";
$smtp_username = "info@urbanofficetx.com";
$smtp_password = "##UOtx1234!";

$email_site_url = "urbanofficetx.com";
$email_site_name = "Urban Office";

#Quickemailverification api key
$quickemailverification_api_key = '4507065a50b873755633e0ce5b71d3d4d1fcb64738482a1254a6997fd643';