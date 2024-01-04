<!DOCTYPE HTML>
<html lang="en">
  <head typeof="og:article">
    <base href="<?php echo $site_url ?>">
    <?php 
      require_once("include/classes/Meta.php");
      $meta_obj = new Meta();
      $meta_data = $meta_obj->page_meta_via_uri($current_full_url, $post_type);
      if($meta_data) foreach ($meta_data as $value) { echo "$value\n";} 

    ?>
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $local_cdn_image; ?>favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $local_cdn_image; ?>favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $local_cdn_image; ?>favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $local_cdn_image; ?>favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $local_cdn_image; ?>favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $local_cdn_image; ?>favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $local_cdn_image; ?>favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $local_cdn_image; ?>favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $local_cdn_image; ?>favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $local_cdn_image; ?>favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $local_cdn_image; ?>favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $local_cdn_image; ?>favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $local_cdn_image; ?>favicon/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
     <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="<?php echo $local_cdn; ?>static/front/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $local_cdn; ?>static/front/css/animations.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $local_cdn; ?>static/front/css/slick.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $local_cdn; ?>static/front/css/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $local_cdn; ?>static/front/css/datepicker3.css">
    <link rel='stylesheet' href='<?php echo $local_cdn; ?>static/front/min/page.min.css?v=<?php echo get_date_mod("static/front/min/page.min.css"); ?>' type='text/css' media='screen' />
    <link rel="stylesheet" type="text/css" href='<?php echo $local_cdn; ?>static/front/css/style.css?v=<?php echo get_date_mod("static/front/css/style.css"); ?>'/>
    <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">

    <?php 
      $meta_data = $meta_obj->get_page_meta_by_key(1, 'header_script');
      $header_script = isset($meta_data) ? $meta_data['meta_value']: '';
      echo $header_script;
    ?></head>
  <body>