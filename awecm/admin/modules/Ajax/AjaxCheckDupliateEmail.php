<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once('include/classes/SystemUser.php');

$focus = new SystemUser();

$email    = $_REQUEST['email'];
$user_id  = $_REQUEST['id'];

if($email && $user_id) {
  $result = $focus->check_duplicate_email($user_id, $email);
  echo $result;
}
else if($email) {
  $result = $focus->check_duplicate_email('', $email);
  echo $result;
}
