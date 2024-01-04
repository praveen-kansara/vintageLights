<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if(!defined('__URBAN_OFFICE__')) exit;

require_once("include/classes/SystemUser.php");
$obj_sys_user = New SystemUser();

require_once("include/classes/Meta.php");
$obj_meta = New Meta();

$response = [];

# Purpose: Save the Application
require_once("include/classes/Application.php");
$application_obj  = New Application();

$valid_fields =[
    'suiteno', 
    'duration_term', 
    'start_date', 
    'location', 
    'businesstype', 
    'peoplenumber', 
    'legalname',
    'contactperson',
    'phonenumber',
    'email',
    'homeaddress',
    'home_street', 
    'home_city',
    'home_state',
    'home_zip',
    'hearabout',
    'filed_for_bankruptcy',
    'convictiontype',
    'sexoffender',
    'additionalinformation',
    'guarantorsname', 
    'guarantorstitle', 
    'guarantorsdob', 
    'guarantorsemail', 
    'homestreet', 
    'homecity', 
    'homestate', 
    'homezip',
    'cert_printed_name', 
    'cert_title', 
    'cert_date'
];
$error_field = [];


foreach($_POST as $field_key => $field_value){
   
   $_SESSION['form_data'][$field_key] = $field_value;
   
   if(in_array($field_key, $valid_fields ))  {
       if($field_value == ""){
           $t =[];
           $t[] = ['field_name' => $field_key, 'message' => " is a required field"] ;
           $error_field[] = $t;
       }
   }
//   print_r($error_field);die;

}

// Validation for file upload
if(!$_FILES['file_dl']['name']) {
    $t =[];
    $t[] = ['field_name' => 'file_dl', 'message' => " is required!"] ;
    $error_field[] = $t;
}

$member_form_url = $_POST['member_form_url'];
  
if(!empty($error_field)) {
    $response['error'] = true;
    $response['type'] = 'validation';
    $response['data'] =  $error_field;
    $_SESSION['message'] = $response;
    redirect($member_form_url);
    die;
    echo json_encode($response); die;
} else {
    $response['error'] = false;
    $response['type'] = '';
}
//$response['error'] = false;
$application_obj->name = $_POST['legalname'];
$application_obj->email = $_POST['email'];
$application_obj->post_data = json_encode($_POST);

$application_obj->save();

if($_FILES['file_dl']['name']) {
    //echo print_r($_FILES,1);

    $filename = $_FILES['file_dl']['name'];
    $file_extension = '.'.pathinfo($filename, PATHINFO_EXTENSION);
    $base_file_name = basename($filename, ".".pathinfo($filename, PATHINFO_EXTENSION));

   # Creating directories in image path directory
    //$path = "../".trim($uploaded_image_path);
//    $upload_path = '/var/www/html/urbanofficetx.local/public_html/';
    $md5  = md5($base_file_name);
    $path = 'media/';
    for($i = 0; $i < 6; $i++) {
      $path = $path . $md5{$i} . '/';
      if(!is_dir($path)) {
        @mkdir($path);
        @chmod($path, 0777);
      }
    }
    $file_path  =  $path;
    
    $full_path = $upload_path.$path.$base_file_name.$file_extension;
    
    $has_attachment = false;
    
    if(move_uploaded_file($_FILES['file_dl']['tmp_name'], $full_path)) {

        $application_obj->filename = $filename;
        $application_obj->path = $file_path;
        $application_obj->save();
        $has_attachment = true;
      //  echo json_encode(['success' => 'OK', 'message' => '']);
    } else {
      //  echo json_encode(['success' => 'NOT_OK',  'message' => '']);
    }

}

    $email_settings_data = $obj_meta->get_page_meta_by_key('1','email_settings');
    $site_name_data = $obj_meta->get_page_meta_by_key('1','site_name');
    
    $from_site_name = !empty($site_name_data['meta_value']) ? $site_name_data['meta_value'] : $site_name ;
    
    $site_email_meta = $obj_meta->get_page_meta_by_key(1, 'site_email');
    $site_email = $site_email_meta['meta_value'];
    
    $user_email_body = $reply_to_email = '';
    
    $admin_email = $site_email;
    $admin_name  = "";
    
    $email_site_name = 'Urban Office';
    
    $from = [
        "name" => $email_site_name,
        "email" => 'info@urbanofficetx.com',
    ];
    
    
   // if(!empty($admin_email)) {
    
        $cust_name = $application_obj->name;
        $email = $application_obj->email;
        
        $from_admin = [
            "name"  => $cust_name,
            "email" => 'info@urbanofficetx.com',
        ];
        
        $to_array_admin = [
            [
                "name"  => $admin_name,
                "email" => $admin_email,
            ],
            [
                'name' => 'UrbanOffice San Antonio',
                'email' => 'sanantonio@urbanofficetx.com',
                
            ]
        ];
        
        $bcc = [
            [
                'name' => 'Braun Emails',
                'email' => 'awbraun2020@gmail.com'
            ]
        ];
        
        
        $replyto = [
            "name"  =>  $cust_name,
            "email" => $application_obj->email,
        ];
         
    
        $admin_email_subject = 'New Member Application from  '.$cust_name.' on '.$email_site_name.' website';
        
        $email_body = '<table width="100%">';
        
        foreach($form_section as $section) {
        	$email_body .= '<tr><td colspan="2">&nbsp;</td></tr>';
        	$email_body .= '<tr><td colspan="2"><b>'.$section['section_title'].'</b></td></tr>';
            
        	foreach($section['fields'] as $form_field_name => $form_field_label){
              
            	$email_body .= '<tr><td width="30%">'.$form_field_label.'</td><td>'.$_POST[$form_field_name].'</td></tr>';
            }
          }
        
        $email_body .= '</table>';
        
        
        
        $cust_message = $email_body;
       
        
        $admin_email_body = "<p style='color: #333; font-size: 15px;margin-top:0px;margin-bottom: 15px;'>Following are submission details from $cust_name ( $email )</p><br/>$cust_message";
        
        $email_response = send_email($to_array_admin, $from_admin, $replyto, $admin_email_subject, $admin_email_body, true, $bcc, $full_path);  
        $response['data'] =  $email_response;
        $_SESSION['message'] = $response;
        redirect($member_form_url);
     //echo json_encode($response);
     
   // }

die;