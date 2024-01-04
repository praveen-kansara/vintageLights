<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/Menu.php");

$menu_obj  = new Menu();
$menu_meta = new Meta();

$build_menu = array();
$menu = $menu_obj->get_menu_by_name("Primary");

if(!empty($menu)) $build_menu = prepare_menu($menu->id, "");

function printPrimaryMenu($build_menu) {

  global $current_full_url, $post_type;
  $menu_meta = new Meta();

  $menu_str = '';

  if(!empty($build_menu)) {

    $menu_str .= '<ul class="navbar-nav">';

    foreach ($build_menu as $menu_key => $menu_value) {
     $parent_menu_class = '';
     $meta_class_res = $menu_meta->get_page_meta_by_key($menu_value['id'], 'menu_item_css');
     if($meta_class_res['meta_value']){
       $parent_menu_class = $meta_class_res['meta_value'];
     }

     $menu_link = "#";
      if(!empty($menu_value['menu_url'])){
        $menu_link = $menu_value['menu_url'];
      }

      $parent_active_class = '';
      if($current_full_url == $menu_link) {
        $parent_active_class = 'active';
      }

      if($menu_value['slug'] == 'property') {
        $parent_active_class = 'active';
      }

      $nav_item_dropdown = '<li class="nav-item'.$parent_menu_class.' '.$parent_active_class.'" >';
      $nav_link = '<a class="nav-link" href="'.$menu_link.'">'.$menu_value['name'].'</a>';

      if(!empty($menu_value['child'])) {

        $child_uri_array = array();

        foreach ($menu_value['child'] as $ck => $cv) {
          if(!empty($cv['menu_url'])) {
            $child_uri_array[] = trim($cv['menu_url']);
          }
        }

        if(in_array($current_full_url, $child_uri_array)) {
          $parent_active_class = 'active';
        }

          $nav_item_dropdown = '<li class="nav-item dropdown '.$parent_menu_class.' '.$parent_active_class.'">';
          $nav_link = '<a class="nav-link dropdown-toggle" href="'.$menu_link
                      .'"  data-toggle="dropdown" id="navbarDropdown" role="button" aria-haspopup="true" 
                      aria-expanded="false">'
                      .$menu_value['name'].'</a>';
      }

      $menu_str .= $nav_item_dropdown;
      $menu_str .= $nav_link;

      if(!empty($menu_value['child'])) {

        $menu_str .='<div class="dropdown-menu" aria-labelledby="navbarDropdown">';

        $child_cnt = 1;
        foreach ($menu_value['child'] as $child_menu_key => $child_menu_val) {
            
           $child_menu_class = '';
           $meta_class_res_child = $menu_meta->get_page_meta_by_key($child_menu_val['id'], 'menu_item_css');
           if($meta_class_res_child['meta_value']){
             $child_menu_class = $meta_class_res_child['meta_value'];
           }

          $child_link = "";
          if(!empty($child_menu_val['menu_url'])){
            $child_link = trim($child_menu_val['menu_url']);
          }

          $child_active_class = '';
          if($current_full_url == $child_link) {
            $child_active_class = 'active';
          }

          # 3 level menu
          if(!empty($child_menu_val['child'])) {
          } else {
            $menu_str .= '<a class="dropdown-item '.$child_menu_class.' '.$child_active_class.'" 
                            href="'.$child_link.'">'.$child_menu_val['name'].'</a>';
          }
        }

        $menu_str .= '</div>';
      }
      $menu_str .= '</li>';
    }

    $menu_str .= '</ul>';
  }

  return $menu_str;

}

?>
<header class="sticky-top site-header">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="logo">
        <a class="navbar-brand" href="./">
          <img src="<?php echo $local_cdn_image; ?>urban-office-logo.svg" title="Urban Office" alt="Urban Office" width="230" height="33">
           <span class="subtitle">A Braun Enterprises Company</span>
        </a>
        <a href="https://www.braunenterprises.com/" class="header-braun-logo" target="_blank"><img src="<?php echo $local_cdn_image; ?>braun-enterprises-logo.png" title="Braun Enterprises" alt="Braun Enterprises" width="80" height=""></a>
      </div>
     
      <button id="btn_nav" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </span>
      </button>
      <div class="collapse navbar-collapse justify-content-center align-items-center" id="main-nav">
          <?php  echo printPrimaryMenu($build_menu); ?>
          <ul class="book-tour">
            <li class="nav-item dropdown ">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">Book a Tour</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php foreach ($calendly_loaction_link as $location_value) {?>
                <a class="dropdown-item " data-target="book-a-tour" href="#" onclick="Calendly.initPopupWidget({url: '<?php echo $location_value['calendly_url'];?>'});return false;"><?php echo $location_value['name'];?></a>
                <?php }?>
              </div>
            </li>
          </ul>
          
          <ul class="book-tour call-now-dropdown"> 
          <li class="nav-item dropdown "> <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">Call Now</a> 
          <div class="dropdown-menu" aria-labelledby="navbarDropdown"> 
              <a class="dropdown-item " href="tel:8328273999"><small>Houston:</small> 832-827–3999</a> 
              <a class="dropdown-item " href="tel:2104359515"><small>San Antonio:</small> 210-435-9515</a>
              <a class="dropdown-item " href="tel:2104359515"><small>Austin:</small> 512-505-0008</a>
          </div> 
         </li> 
        </ul>
      </div>
    </nav>
  </div>
</header>


