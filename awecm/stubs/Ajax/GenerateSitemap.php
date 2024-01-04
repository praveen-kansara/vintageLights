<?php
if(!defined('__URBAN_OFFICE__')) die('Invalid Access');

ini_set("display_errors", 0);


function generate_url_set($post_type='page', $priority="1.0") {
    global $db;

    $q = "select uri, date_modified 
        from page 
        where 
            deleted = 0 and 
            post_type='$post_type' and 
            status = 'published'";

    $items = $db->get_results($q, ARRAY_A);

    $xml_string = '';
    if($items) {
        foreach($items as $item) {
            $xml_string .= generate_url_block($item['uri'], $item['date_modified'], $priority);  
        }
    }
    
    return $xml_string;
}


function generate_url_block( $uri, $date_modified, $priority) {
    $xml = '';

    $lastmod     = new DateTime($date_modified);
    $lastmod_str = $lastmod->format('Y-m-d\TH:i:sP');

    $xml .= '<url>';
    $xml .= '<loc>'.$uri.'</loc>';    
    $xml .= '<lastmod>'.$lastmod_str.'</lastmod>';    
    $xml .= '<priority>'.$priority.'</priority>';    
    $xml .= '</url>';

    return $xml;
}

header ("Content-Type:text/xml");

$xml_sitemap =<<< SITEMAP
<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
SITEMAP;

$xml_sitemap .= generate_url_set('page', '1.0');
$xml_sitemap .= generate_url_set('property', '1.0');
$xml_sitemap .= generate_url_set('press', '0.6');
$xml_sitemap .= generate_url_set('blog', '0.8');

$xml_sitemap .=<<< SITEMAP
</urlset>
SITEMAP;

echo $xml_sitemap;