<?php
if(!defined('__URBAN_OFFICE__')) exit;

if(isset($_POST['user_email'])) {

  require_once("include/classes/SystemUser.php");
  $focus = new SystemUser();

  $remeber_me = isset($_POST['remember_me']) ? $_POST['remember_me'] : "";

  if(!$focus->authenticate_user($_POST['user_email'], $_POST['user_password'], $remeber_me)) {
    redirect('./?module=SystemUser&action=Login&auth=False');
  }
  else {
     $error_url = $site_url."sarkar/?module=SystemUser&action=Login&auth=False";

    if(!empty($admin_current_full_url) && $admin_current_full_url != $error_url) {
      redirect($admin_current_full_url);
    } else {
      redirect('./?module=Page&action=index');
    }
  }

}
