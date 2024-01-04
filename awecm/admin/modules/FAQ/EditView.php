<?php
if( !defined('__URBAN_OFFICE__') ) exit;

$xtpl= new XTemplate("modules/FAQ/EditView.html");

require_once("include/classes/Page.php");
$focus = new Page();

require_once("include/classes/PageMedia.php");
$page_media_obj = new PageMedia();

require_once("include/classes/Meta.php");
$page_meta_obj = new Meta();

$mode = "Add";
$faq_path = "faq/";

$xtpl->assign('SEARCH_STATUS', select_list($content_status_dom, '', 1));

if(isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) ) {
  $xtpl->assign('SUCCESS_MESSAGE', $_SESSION["msg"]);
  $xtpl->parse('main.msg');
  unset($_SESSION["msg"]); 
}

$xtpl->assign('HIDE_CLASS', "hidden");

if(!empty($_REQUEST['id'])) {

  $res = $focus->retrieve($_REQUEST['id']);
  if(!empty($res)) {

    $link = $site_url.$faq_path."<span data-slug='".$focus->slug."'>".$focus->slug."/</span>";
    $redirect_link = $site_url.$faq_path.$focus->slug."/";

    $xtpl->assign('ID',         $_REQUEST['id']);
    $xtpl->assign('HIDE_CLASS', "");
    $xtpl->assign('QUESTION',   $focus->title);
    $xtpl->assign('SLUG_CLASS', "");
    $xtpl->assign('SLUG_EXISTS','slug-exists');
    $xtpl->assign('PERMALINK',  $link);
    $xtpl->assign('PAGE_SLUG',  $focus->slug);
    $xtpl->assign('ANSWER',    $focus->content);
    $xtpl->assign('SEQUENCE',  $focus->sequence);

    if($focus->status == 'published') {
      $xtpl->assign('HIDE_VIEW_BTN', '');
      $xtpl->assign('PAGE_LINK', $redirect_link);

    } else {
      $xtpl->assign('HIDE_VIEW_BTN', 'hidden');
    }

    $mode = "Edit";

  } else redirect("./?module=FAQ&action=index");


} else {
  $xtpl->assign('SEARCH_STATUS', select_list($content_status_dom, "", 1));
  $xtpl->assign('SLUG_CLASS', "hidden");
}

$xtpl->assign('MODE', $mode);
$xtpl->parse('main');
$xtpl->out('main');
