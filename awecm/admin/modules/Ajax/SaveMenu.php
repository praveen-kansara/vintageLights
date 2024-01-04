<?php
if(!defined('__URBAN_OFFICE__')) exit;

require_once('include/classes/MenuItem.php');
$focus = new MenuItem();

require_once('include/classes/Meta.php');
$meta_obj = new Meta();

# flushing menu_item table before storing new menu items

$focus->delete_menu_and_meta($_REQUEST['menu_id']);

foreach($_REQUEST['menus'] as $key => $menu_item) {
  
  if($key!=0) {

    $focus->save_menu_itmes([
      'id'               => $menu_item['id'], 
      'name'             => $menu_item['name'],
      'parent_id'        => $menu_item['parent_id'],
      'menu_id'          => $_REQUEST['menu_id'],
      'type'             => $menu_item['type'],
      'page_id'          => $menu_item['page_id'],
      'url'              => $menu_item['url'],
      'display_sequence' => $menu_item['display_sequence']
    ]);

    $meta_obj->save_page_meta($menu_item['id'], 'menu_item_css', $menu_item['css_class'], 'menu');
    $meta_obj->save_page_meta($menu_item['id'], 'menu_item_target', $menu_item['target'], 'menu');

  }
  
}

echo json_encode(['status'=>'success']);
