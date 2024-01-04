<?php

if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/MenuItem.php");
$focus = new MenuItem();

require_once("include/classes/Menu.php");
$obj_menu = new Menu();

$id = $_REQUEST['id'];

$obj_menu->delete_menu($id);
$focus->delete_menu($id);
$focus->delete_menu_and_meta($id);

$return_url = "./?module=Menu&action=index";
redirect($return_url);
