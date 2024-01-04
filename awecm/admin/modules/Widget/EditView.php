<?php
if(!defined('__URBAN_OFFICE__')) exit;

$xtpl= new XTemplate("modules/Widget/EditView.html");

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/PageMedia.php");
$page_media_obj = new PageMedia();

require_once("include/classes/Meta.php");
$meta = new Meta();

$mode = "Add";

if(isset($_SESSION["msg"])) {

  if($_SESSION["msg"] == 'published'){ $xtpl->assign('SUCCESS_MESSAGE', _PUBLISHED_MSG); }
  else if($_SESSION["msg"] == 'draft'){ $xtpl->assign('SUCCESS_MESSAGE', _DRAFT_MSG); }

  $xtpl->parse('main.msg');
  unset($_SESSION["msg"]); 
}

$xtpl->assign('HIDE_CLASS', "hidden");

if(!empty($_REQUEST['id'])) {

  $xtpl->assign('HIDE_CLASS', "");

  $focus->retrieve($_REQUEST['id']);

  $xtpl->assign('ID',                     $focus->id);
  $xtpl->assign('PAGE_TITLE',             $focus->title);
  $xtpl->assign('SEQUENCE',               $focus->sequence);
  $xtpl->assign('SLUG_CLASS',             "");
  $xtpl->assign('SLUG_EXISTS',            'slug-exists');
  $xtpl->assign('PERMALINK',              $link);
  $xtpl->assign('PAGE_SLUG',              $focus->slug);
  $xtpl->assign('PAGE_CONTENT',           $focus->content);
  $xtpl->assign('PAGE_SHORT_DESCRIPTION', $focus->short_description);
  $xtpl->assign('SEARCH_STATUS', select_list($content_status_dom, $focus->status, 1));

  $page_meta_array =  $meta->get_all_page_meta($_REQUEST['id']);
  $short_code = isset($page_meta_array['short_code']) ? $page_meta_array['short_code'] : "";
  $xtpl->assign('SHORT_CODE', $short_code);
  $xtpl->assign('SHORT_CODE_STR', "<span data-short-code='".$short_code."'>".$short_code."</span>");
 
  $mode = "Edit";

}
else {
  $xtpl->assign('SEARCH_STATUS', select_list($content_status_dom, "", 1));
  $xtpl->assign('SLUG_CLASS', "hidden");
}

$xtpl->assign('MODE', $mode);
$xtpl->parse('main');
$xtpl->out('main');
