<?php 
?>

<footer class="footer-block">
  <a id="button" class="back-top"><img src="<?php echo $local_cdn_image; ?>back-to-top-btn.svg" width="58" height="58"
    alt="Back To Top" title="Back To Top"></a>
  <div class="footer-main">
    <div class="container">
      <div class="row">
         <?php echo parse_short_code('{FOOTER_COLUMN_ONE}', 'widget'); ?>
         <div class="col-12 col-md-3">
          <div class="footer-widget-section location-widget">
            <h3>Locations</h3>
            <ul class="footer-nav">
               <?php
                  $build_menu = array();
                  $fmenu = $menu_obj->get_menu_by_name("Footer Location Menu");
                  if(!empty($fmenu)) {
                    $build_footer_menu = prepare_menu($fmenu->id, "");
                    if(!empty($build_footer_menu)) {
                      foreach ($build_footer_menu as $mvalue) {
                        echo '<li><a href="'.$mvalue['menu_url'].'">'.$mvalue['name'].'</a></li>';
                      }
                    } 
                  }
                ?>
                
            </ul>
            

            <a class="btn btn-primary" href="/?q=Home/MemberForm">
              Application 
            </a>

          
            
          </div>
         </div>
         <?php echo parse_short_code('{FOOTER_COLUMN_3}', 'widget'); ?>

      </div>
    </div>
    <div class="braun-site-brand">
      <div class="footer-bottom-box">
        <p>A Braun Enterprises Company</p>
        <a href="https://www.braunenterprises.com/">
          <img src="static/front/images/braun-enterprises.png" alt="Braun Enterprises" 
        title="Braun Enterprises" width="105" height="85"></a>
      </div>
    </div>
  </div>
  <p class="copyright">&copy; <?php echo date('Y');?> Braun Enterprises. All rights reserved. • <a href="/privacy/">Privacy Policy</a> • <a href="/accessibility/">Accessibility</a> • Powered By <a href="https://www.arrowebs.com" target="_blank">arroWebs</a></p>
</footer>

<?php if ($site_url == $current_full_url ) { ?>
  <script src="<?php echo $local_cdn; ?>static/front/min/home.min.js?v=<?php echo get_date_mod("static/front/min/home.min.js"); ?>"></script>
<?php } else {?>
  <script src="<?php echo $local_cdn; ?>static/front/min/page.min.js?v=<?php echo get_date_mod("static/front/min/page.min.js"); ?>"></script>

<?php } ?>

<?php if($template_selected == "property_list_template") { ?>
  <script src='https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.js'></script>
  <link href='https://api.mapbox.com/mapbox-gl-js/v2.2.0/mapbox-gl.css' rel='stylesheet'/>
  <script src="<?php echo $local_cdn; ?>static/front/js/property-list.js?>"></script>
 <?php 
    } else if ($template_selected == "application_form_template") {
 ?>
  <script src="<?php echo $local_cdn; ?>static/front/js/applicationform.js?>"></script>
 <?php
   }
 ?>

<script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript" async></script>

<div id="fb-root"></div>

<?php 
  require_once("include/classes/Meta.php");
  $focus = new Meta();
  $meta_data = $focus->get_page_meta_by_key(1, 'footer_script');
  $footer_script = isset($meta_data) ? $meta_data['meta_value']: '';
  echo $footer_script;
?>
<script>

</script>
 </body>
</html>
