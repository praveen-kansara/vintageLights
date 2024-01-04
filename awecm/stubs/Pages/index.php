<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

require_once("include/classes/Page.php");
$focus = new Page();

$xtpl = new XTemplate("stubs/Pages/index.html");

if(isset($content->content)) {
 $home_page_content = $content->content;
} else {
  $home_page_content ="<h2 class='text-center'> Content not found <h2>";
}

$xtpl->parse('main');
$xtpl->out('main');
