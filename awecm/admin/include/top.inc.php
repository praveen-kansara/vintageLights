<?php if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
        <?php
        $parent_array = [];
        $parents_tab  = '';
        $child_tab    = '';

        foreach($modules_list as $tabs_data) {
          $parent_tab        = $tabs_data['tab'];
          $parent_icon_class = $tabs_data['parent_icon'];
          in_array($module, $tabs_data['module']) ? $class = 'active' : $class = ''; 

          if(!empty($tabs_data['child'])) {
            $parents_tab = <<<EOQ
                            <li class="treeview $class">
                              <a href="#">
                                <i class="$parent_icon_class"></i> <span>$parent_tab</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                              </a>
                            <ul class="treeview-menu">
EOQ;
            array_push($parent_array, $parents_tab);

            if(!empty($tabs_data['child']))
              $children = $tabs_data['child'];

              foreach ($children as $child) {
                $tab        = $child['tab']; 
                $child_icon = $child['icon']; 
                $chid_link  = $child['link']; 
                $bulid_link = str_replace(".","",$chid_link);
                $bulid_link = $site_url.'sarkar'.$bulid_link;
                $child_active_class = ($bulid_link == $admin_current_full_url) ? "active" : "";
                
                $child_tab = <<<EOQ
                                <li class="$child_active_class">
                                    <a href="$chid_link">
                                        <i class="$child_icon"></i>$tab
                                    </a>
                                </li>
EOQ;

                array_push($parent_array, $child_tab);
              }
              $parents_tab = <<<EOQ
                       </ul></li>
EOQ;
            array_push($parent_array, $parents_tab);
          } else {
            $parents_tab = <<<EOQ
                            <li class="treeview $class">
                              <a href="{$tabs_data['link']}">
                                <i class="$parent_icon_class"></i> <span>$parent_tab</span>
                              </a>
                            </li>
EOQ;
            array_push($parent_array, $parents_tab);
          }

        }

        $get_content = implode("", $parent_array);
        echo $get_content;

        ?>
        </ul>
    </section>
</aside>
