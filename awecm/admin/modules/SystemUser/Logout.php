<?php
if(!defined('__URBAN_OFFICE__')) exit;

## Clear cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
  $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
  
  foreach($cookies as $cookie) {
    $parts = explode('=', $cookie);
    $name = trim($parts[0]);
    setcookie($name, '', time()-1000);
    setcookie($name, '', time()-1000, '/');
  }
}

$_SESSION['current_user_id'] = "";
$_SESSION['user_name'] = "";

session_destroy();

redirect("./");
