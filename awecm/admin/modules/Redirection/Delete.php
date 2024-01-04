<?php

if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/Redir.php");
$focus = new Redir();

$id = $_REQUEST['id'];

if($id) { 
  $focus->mark_deleted($id); 
}

create_rdir_json();

$return_url = "./?module=Redirection&action=index";
redirect($return_url);
