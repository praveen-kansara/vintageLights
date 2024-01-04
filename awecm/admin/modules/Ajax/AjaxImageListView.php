<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access!');

require_once("include/classes/Media.php");
$focus = new Media();

$xtpl= new XTemplate("modules/Ajax/AjaxImageListView.html");

if(!empty($_REQUEST['search_count'])) {
  $search_count = $_REQUEST['search_count'];
  $xtpl->assign('SEARCH_COUNT', $search_count);
}

# Set first page or requested page no.
$pageno = 1;
if(!empty($_REQUEST['page_no'])) $pageno = $_REQUEST['page_no'];
$xtpl->assign('PAGENO', $pageno+1);

$start = 0;
if($pageno > 1) {
  $start = ($pageno -1) * $max_entries_media_panel;
}

$limit  = $max_entries_media_panel;

# Check for search params
$where = array();
$where[] = "deleted = 0";

if(!empty($_REQUEST['media_name'])) {
  $media_name = addslashes($_REQUEST['media_name']);
  $where[] = "name LIKE  '%$media_name%'";
}

if(!empty($_REQUEST['page_filter'])) {

  $table_name = '';
  $page_filter = addslashes($_REQUEST['page_filter']);

  $media_ids = $focus->get_media_id($page_filter, $table_name);

  if(!empty($media_ids)) {
    $media_str = implode("','", $media_ids);
    $where[] = "id IN('$media_str')";
  } else {
    $where[] = "id = ''";
  }

}

$where_clause = build_where_clause($where);
$images = $focus->get_media_attachments($start, $limit, $where_clause);

# Loader image
$loader_path = $site_url."static/images/spinner.gif";
$xtpl->assign('LOADER_PATH', $loader_path);

if(count($images) > ($max_entries_media_panel-1)) {
  $xtpl->parse('main.pagination_item');
}

if(count($images) > 0) {
  $cont = 0;
  foreach ($images as $key => $image) {
    $cont++;
    $image_meta              = json_decode($image['attributes'], true);
    $image_name              = $image['name'];
    $thumbnails              = get_all_thumbnails_path($image['path'], $image_name);
    $image['alt']            = $image_meta['alt'];
    $image['thumbnail_path'] = $thumbnails[0];

    $xtpl->assign('ID', $image['id']);
    $xtpl->assign('row', $image);

    $xtpl->parse('main.imageitem');
  }

} else {
  $xtpl->parse('main.no_result');
}


$javascript = <<<EOQ
<script type='text/javascript'>
$( document ).ready(function() {
  $(".load_more").on('click', function() {
    var media_name = $('#media_name').val();
    var page_filter = $('#filters').val();
    get_images(media_name, page_filter);
  })
});

function get_images(media_name, page_filter) {

  var page_no      = $('.load_more').data("pageno");
  var search_count = $('.load_more').data("search-count");

  if(media_name != '' || page_filter != '') {
    if(search_count > 0 ) {
       search_count = search_count+1;
    } else { 
      search_count = 1;
      page_no = 1;
    }
  }

  if((media_name != '' || page_filter != '') && search_count == 1) {
    $(".lib-list").html('');
  }

  $.ajax({
    type: 'POST',
    dataType: "html",
    url: "./?module=Ajax&action=AjaxImageListView",
    data: { 
      page_no: page_no,
      media_name:media_name,
      page_filter:page_filter,
      search_count:search_count,
    },
     beforeSend: function() {
       $(".lib-list").append('<div class="media-page-loader"></div>');
       $(".page_par").html('');
       $(".loader_par").show();
     },
     success: function(html) {
      $(".media-page-loader").remove();
      $(".load_more_paging").remove();
      $(".lib-list").append(html);

      if((media_name != '' || page_filter != '') && search_count == 1) {
        $(".load_more").attr('data-pageno', 2);
      }

     }

  });

}
</script>
EOQ;

$xtpl->assign('JAVASCRIPT', $javascript);
$xtpl->parse('main');
$xtpl->out('main');
