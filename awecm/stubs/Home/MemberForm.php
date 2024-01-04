<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

$no_cache = true;

$max_entries_per_page = 20;

require_once("include/classes/Page.php");
$obj_page = new Page();

require_once("include/classes/Meta.php");
$objPageMeta = new Meta();

require_once("include/classes/Media.php");
$objPageMedia = new PageMedia();

$xtpl = new XTemplate("stubs/Home/MemberForm.html");

$xtpl->assign("CURRENT_FULL_URL",$current_full_url);

if(isset($_SESSION['message']) && $_SESSION['message']['error'] == '1') {
    
    foreach( $_SESSION['message']['data'] as $error_key => $error_data) {
       // print_r($error_data);
        $xtpl->assign("ERROR_MESSAGE", $form_valid_fields[$error_data[0]['field_name']].' '. $error_data[0]['message']);
        $xtpl->parse("main.error_section.error_fields");
        
    }
     $xtpl->parse("main.error_section");
} 


//  fill post values if you have a session

if(isset($_SESSION['form_data']) ) {
    foreach($_SESSION['form_data'] as $field_name => $field_value){
        $xtpl->assign(strtoupper($field_name), $field_value);
    }
}

if (isset($_SESSION['message']) && $_SESSION['message']['error'] != '1' ){
    $xtpl->assign('THANKYOU_MESSAGE' , 'Thank you for the submission!');
    $xtpl->parse("main.thankyou_message");
    unset($_SESSION['message']);
    unset($_SESSION['form_data']);
} else {
    $xtpl->parse('main.the_form');
}



$xtpl->parse('main');
echo $xtpl->text('main');
