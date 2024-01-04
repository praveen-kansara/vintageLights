<?php
if(!defined('__URBAN_OFFICE__')) exit;

# Purpose: Save the Enquiry
require_once("include/classes/Enquiry.php");
$enquiry_obj  = New Enquiry();

require_once("include/classes/SystemUser.php");
$obj_sys_user = New SystemUser();

require_once("include/classes/Meta.php");
$obj_meta = New Meta();

require_once("include/classes/Page.php");
$obj_page = new Page();

# Site Details:

$email_settings_data = $obj_meta->get_page_meta_by_key('1','email_settings');
$site_name_data      = $obj_meta->get_page_meta_by_key('1','site_name');

$from_site_name = !empty($site_name_data['meta_value']) ? $site_name_data['meta_value'] : $site_name ;


$site_email_meta = $obj_meta->get_page_meta_by_key(1, 'site_email');
$site_email = $site_email_meta['meta_value'];

$user_email_body = $reply_to_email = '';

$admin_email = $site_email;
$admin_name  = "";

if(isset($_REQUEST['type']) && $_REQUEST['type'] == "ajax") {
  
  $valid_email = check_email_validity($_REQUEST['email']);

  if($valid_email) {
      foreach($enquiry_obj->column_fields as $field) {

        if(isset($_REQUEST[$field])) {
          ${$field} = $_REQUEST[$field];
          if($field == 'move_in_date') {
            $value = $_REQUEST[$field];
            if(!empty($value)) {
              $value = date('Y-m-d H:i:s',strtotime($value));
            }
          } else {
            $value = $_REQUEST[$field];
          }
          $enquiry_obj->$field = $value;
        }
      }
    
      if($_REQUEST['user_agent'] && $_REQUEST['ip']) {
    
        $usr_del = array(
          'ip'      => $_REQUEST['ip'],
          'browser' => $_REQUEST['user_agent']
        );
        $enquiry_obj->user_detail = json_encode($usr_del);
    
      }
    
      $enquiry_obj->save();
      
      if(!empty($property_id)) {
        $where_array = array(
         "id = '".$property_id."'",
         "post_type = 'property'",
        );
        
        $property_detail = $obj_page->get_page_by_condition($where_array);
        $property_name = $property_detail->title;    
      } else $property_name = '';
    
      $cust_name = !empty($enquiry_obj->last_name) ? $enquiry_obj->first_name.' '.$enquiry_obj->last_name : $enquiry_obj->first_name;
    
      $from = array(
        "name"  => $email_site_name,
        "email" => 'info@urbanofficetx.com',
      );
    
      # Set details for the Admin
    
      if(!empty($admin_email)) {
          
       $from_admin = array(
        "name"  => $cust_name,
        "email" => 'info@urbanofficetx.com',
       );
    
       $to_array_admin = array(
         "name"  => $admin_name,
         "email" => $admin_email,
       );
    
       $replyto = [
        'email' => $email,
        'name'  => $cust_name,
       ];

       $bcc = [
          [
            'name' => 'Braun Emails',
            'email' => 'awbraun2020@gmail.com'
          ]
       ];
    
       $admin_email_subject = 'Inquiry for '.$property_name.' from '.$cust_name.' on '.$email_site_name.' website.';
       
       $cust_phone    = (!empty($phone))   ? $phone   : '';
       $cust_message  = (!empty($message)) ? $message.'<br/><br/>' : '';
    
       $admin_email_body = "<p style='color: #333; font-size: 15px;margin-top:0px;margin-bottom: 15px;'>".$cust_name." (".$email.") $cust_phone is interested in ".$property_name." and sent the following message;</p>".$cust_message."Move in date: $move_in_date<br/>No of people: $no_of_people";
    
       $email_response = send_email($to_array_admin, $from_admin, $replyto, $admin_email_subject, $admin_email_body, true, $bcc);  
      }
      #-------------------------------------------------------------------------------------
      # Create details to send email to user
    
      $to_array_user = array(
        "name"  => $enquiry_obj->first_name.' '.$enquiry_obj->last_name,
        "email" => $enquiry_obj->email,
      );
    
      $email_subject = $cust_name.' - Thank you for the inquiry';
    
      $user_email_body = "<p style='color: #333; font-size: 15px;margin-top:0px;margin-bottom: 15px;'>Hi {$cust_name},</p><p style='color: #333; font-size: 15px;margin-top:0px;margin-bottom: 15px;'> Thank you for visiting {$email_site_url} and sending this inquiry.</p> <p style='color: #333; font-size: 15px;margin-top:0px;margin-bottom: 15px;'>One of us will contact you shortly.</p><p style='color: #333; font-size: 15px;margin-top:0px;margin-bottom: 15px;'>Team {$email_site_name}</p>";
    
    
      $email_response = send_email($to_array_user, $from, null, $email_subject, $user_email_body, true, $bcc);
    
      echo json_encode([
        'id'     => $enquiry_obj->id, 
        'status' => 'success', 
        'message'=> 'Request submitted successfully'
      ]);
  } else {
    echo json_encode([
      'status'  => 'failure', 
      'message' => 'Please enter a valid email address'
    ]);  
  }

  
} else {
  echo json_encode([
    'status'  => 'error', 
    'message' => 'Invalid Request'
  ]);
}