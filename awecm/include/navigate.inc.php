<?php
if(!defined('__URBAN_OFFICE__')) exit;


/**
    Build Navigation Section
    This is to layout a navigation table with appropriate URLs
*/

function _build_navigation($entries, $offset, $num_spans) {
    global $module;
    global $max_entries_per_page;
    $nav = "";
    if($entries) {
        $nav = "";
        // Column 1 showing the numbers
        $shown = min($offset + $max_entries_per_page, $entries);
        $firstCount = $offset + 1 ;
        $secondCount = $shown ;
        $thirdCount = $entries ;
        $prevNextCode = "";
        // Previous Page
        $set_offset = $offset - $max_entries_per_page;
        if($set_offset <= 0) $set_offset = 0;
        if($offset && $set_offset >= 0) {
            $prevNextCode = '<a href="javascript:Navigate('.$set_offset.');" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>';
            $nav .= '<a class="page" href="javascript:Navigate('.$set_offset.');" title="Show Previous"><img class="prev" src="images/spacer.gif" height="10" width="10" border="0" />Previous</a>';
        }
        else {
            $prevNextCode = '<a disabled="true" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>';
            $nav .= ' <img class="prevoff" src="images/spacer.gif" height="10" width="10" />Previous';
        }
        // Next Page
        $set_offset = $offset + $max_entries_per_page;
        if($set_offset >= $entries) $set_offset = 0;
        if($set_offset) {
            $prevNextCode .= '<a href="javascript:Navigate('.$set_offset.');" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>';
        }
        else {
            $prevNextCode .= '<a disabled="true" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>';
        }
        // Last Page
        $residue = ($entries % $max_entries_per_page);
        if ($residue) $set_offset = $entries - $residue;
        else $set_offset = $entries - $max_entries_per_page;
        if($set_offset == $offset) $set_offset = 0;    // recheck this on 20+
        $pages = ceil($entries / $max_entries_per_page);
        $page = ceil($offset / $max_entries_per_page) + 1;
        $nav = '<tr>
        <td colspan="'.$num_spans.'">
        Page <input class="text-center" type="text" value="'.$page.'" size="2" onchange="javascript:PageNavigate(this.value);" onsubmit="javascript:PageNavigate(this.value);" /> of '.$pages.
        '</td><td>
        '.$firstCount.' - '.$secondCount.' of '.$thirdCount.' &nbsp;&nbsp;
        <div class="btn-group">
        '.$prevNextCode.'
        </div>
        </td>
        </tr>';
    }
    return $nav;
}

function get_page_navigation($max_pages, $offset, $p) {

  $temp = $max_pages % 5;
  if($temp !=  0) $temp = $max_pages - $temp + 1;
  else $temp = $max_pages - 4;

  $nav = '';

  if($max_pages > 1) {
    if($max_pages <= 5) {

      for($i=1; $i <= $max_pages; $i++) {

        $nav .= (($p == $i)? '&nbsp;<span class="nav2">'.$i.'</span>' : '<a href="'.nav_build_url($offset, $i).'" class="nav">&nbsp;<u>'.$i.'</u></a>');
        $nav .= ' ';

      }

    } elseif($offset == 1) {

      for($i=1; $i <= 5; $i++) {

        $nav .= (($p == $i)? '&nbsp;<span class="nav2">'.$i.'</span>' : '<a href="'.nav_build_url($offset, $i).'" class="nav">&nbsp;<u>'.$i.'</u></a>');
        $nav .= ' ';

      }

      $nav .= '<a href="'.nav_build_url($offset + 5, $i).'" class="nav">>></a> ';
      $nav .= '<a href="'.nav_build_url($temp, $max_pages).'" class="nav">'.$max_pages.'</a>';

    } else {

      if($offset <= $max_pages && $offset > $max_pages - 5) {

        $i = $offset;
        $nav .= '<a href="'.nav_build_url(1, 1).'" class="nav">1</a> ';
        $nav .= '<a href="'.nav_build_url($offset - 5, $offset - 5).'" class="nav"><<</a> ';

        for($i; $i <= $max_pages; $i++) {

          $nav .= (($p == $i)? '&nbsp;<span class="nav2">'.$i.'</span>' : '<a href="'.nav_build_url($offset, $i).'" class="nav">&nbsp;<u>'.$i.'</u></a>');
          $nav .= ' ';

        }

      } else {

        $i = $offset;
        $nav .= '<a href="'.nav_build_url(1, 1).'" class="nav">1</a> ';
        $nav .= '<a href="'.nav_build_url($offset - 5, $offset - 5).'" class="nav"><<</a> ';

        for($i; $i <= $offset + 4; $i++) {

          $nav .= (($p == $i)? '&nbsp;<span class="nav2">'.$i.'</span>' : '<a href="'.nav_build_url($offset, $i).'" class="nav">&nbsp;<u>'.$i.'</u></a>');
          $nav .= ' ';

        }

        $nav .= '<a href="'.nav_build_url($offset + 5, $i).'" class="nav">>></a> ';
        $nav .= '<a href="'.nav_build_url($temp, $max_pages).'" class="nav">'.$max_pages.'</a>';

      }

    }
  }

  return $nav;

}

//start of pagination function
function get_page_pagination($max_pages, $offset, $p){

  $pagination = '';

  if($max_pages > 1) {
    if(($p % 5) == 0) $max_offset = $p + 1;
    else $max_offset = $offset;

    if(($p % 5) == 1) $min_offset = $p - 5;
    else $min_offset = $offset;

    $pagination .= (($p != 1)? '<a href="'.nav_build_url($min_offset, $p - 1).'" class="nav">< Previous</a>' : '<span class="nav2">< Previous</span>');
    $pagination .= ' | ';
    $pagination .= (($p != $max_pages)? '<a href="'.nav_build_url($max_offset, $p + 1).'" class="nav">Next ></a>' : '<span class="nav2">Next ></span>');
  }

  return $pagination;

}

function nav_build_url($offset, $p) {

	return "javascript:donavigate('".$offset."', '".$p."')";

}

//end of pgination function
