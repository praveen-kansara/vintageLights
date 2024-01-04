<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

$xtpl= new XTemplate("modules/Menu/ListMenu.html");

require_once("include/classes/Page.php");
$obj_page = new Page();

require_once("include/classes/Menu.php");
$obj_menu = new Menu();

$menu_list = $obj_menu->get_all_menu();

if(!empty($menu_list)) {
  $selected_menu = '';
  if(isset($_REQUEST['menu'])) $selected_menu = $_REQUEST['menu'];
  else $selected_menu = $menu_list[0]->id;

  $xtpl->assign('MENU_LIST', select_list_with_id($menu_list, $selected_menu));

  $pages_list = $obj_page->get_pages_by_post_type('page');

  if(!empty($pages_list)) {
    foreach ($pages_list as $key => $page) {
      $xtpl->assign('PAGE_NAME', strip_tags($page->name));
      $xtpl->assign('PAGE_ID', $page->id);
      $xtpl->parse('main.page_names');
    }
  }

  if(isset($_REQUEST['menu'])) {
    $build_menu = array();
    $build_menu = prepare_menu($_REQUEST['menu'], "");
  }
  else {
    
    //$menu = $obj_menu->get_menu_by_name("Primary");
    $menu = $obj_menu->retrieve($selected_menu);

    $build_menu = array();
    if($menu) $build_menu = prepare_menu($menu->id, "");
  }

  $_sortable_menu = "";

  if($build_menu) {
    foreach ($build_menu as $menu_item) {
      $_sortable_menu .= get_menu_item_template($menu_item);
      $_sortable_menu .= get_menu_child_admin($menu_item);
    }
  }

} else {
  $_sortable_menu = "";
}


$xtpl->assign('BUILD_MENU', $_sortable_menu);
$xtpl->parse('main');
$xtpl->out('main');