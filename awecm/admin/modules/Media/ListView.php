<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/Media.php");
$focus = new Media();

require_once("include/classes/Page.php");
$page_obj = new Page();


$xtpl = new XTemplate("modules/Media/ListView.html");

$xtpl->assign('FRONT_IMG_PATH', $front_img_path);

$xtpl->assign('MEDIALIBARARY', _Media_P);
$xtpl->assign('MEDIA', _Media_S);

$xtpl->assign('FILTER_LIST', select_list($media_post_type_dom, null, 1));

$xtpl->parse('main');
$xtpl->out('main');