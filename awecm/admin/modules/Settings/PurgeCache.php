<?php
if(!defined('__URBAN_OFFICE__')) exit;

remove_cache("", 1);
$return_url = "./?module=Settings&action=EditView";

redirect($return_url);
