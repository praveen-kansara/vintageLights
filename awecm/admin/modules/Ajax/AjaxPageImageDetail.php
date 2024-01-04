<?php
require_once("include/classes/Meta.php");

$page_meta_obj = new Meta();

if($_REQUEST['page_id'])) {

	$page_id      = $_REQUEST['page_id'];
	$image_detail = $page_meta_obj->get_image_detail($page_id);

}