<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $site_name; ?> Administration</title>
  <link rel="shortcut icon" href="../static/images/favicon.ico" type="image/x-icon" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../static/css/bootstrap.min.css" type="text/css">
  <?php
  foreach ($css_references as $link) echo '<link href="'.$link."?v=".get_date_mod($link).'" rel="stylesheet">';
  ?>
  <style type="text/css">
  body {
    font-family: 'Source Sans Pro',sans-serif;
  }
  .custom-button {
    box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 1px 5px 0 rgba(0,0,0,0.12), 0 3px 1px -2px rgba(0,0,0,0.2);
    outline: 0;
    background-color: #fff;
    border: none;
    width: 30px;
    height: 30px;
  }
  #custom-style {
    margin-top:8px
  }
  </style>
<?php if(in_array($module, array('Settings'))) { ?>
<link rel="stylesheet" type="text/css" href="../static/javascript/magicsuggest-master/magicsuggest-min.css">
<?php } ?>

</head>
<?php
$class = "";


if(isset($_COOKIE['sidebar_toggle_collapsed'])) {
  $class = $_COOKIE['sidebar_toggle_collapsed'] == "true" ? "sidebar-collapse" : "";
} ?>

<body class="hold-transition skin-blue sidebar-mini <?php echo $class; ?>">
  <div class="wrapper">
    <header class="main-header">
      <a href="#" class="logo">
        <span class="logo-mini"><img src="<?php echo $site_url; ?>static/images/aw-logo-mini.png" alt="arroWebs" title="arroWebs" width="" height="" /></span>
        <span class="logo-lg"><img src="<?php echo $site_url; ?>static/images/aw-logo-large.png" alt="arroWebs" title="arroWebs" width="" height="" /></span>
      </a>
      <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="logo-title"><?php echo $site_name; ?></li>
            <li>
              <a href="<?php echo $site_url ?>" class="btn btn btn-success" target="_blank">View Site</a>
            </li>
            <li class="logout">
              <a href="./?module=SystemUser&action=Logout" 
                class="btn btn-danger"><i class="fa fa-sign-out"></i></a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
