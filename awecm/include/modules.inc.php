<?php
if( !defined('__URBAN_OFFICE__') ) exit;

$modules_list = [
    'SystemUser' => [
        'tab'  => 'SystemUser',
        'show' => 1,
        'role' => ['admin'],
        'icon' => 'fa fa-users',
        'link' => './?q=SystemUser',
    ],
    'Page' => [
        'tab'  => 'Page',
        'show' => 1,
        'role' => ['admin'],
        'icon' => 'fa fa-book',
        'link' => './?q=Page',
    ],
    'Media' => [
        'tab'  => 'Media Library',
        'show' => 1,
        'role' => ['admin'],
        'icon' => 'fa fa-book',
        'link' => './?q=Media',
    ],
];

$css_references = [
    'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
    'static/css/AdminLTE.min.css',
    'static/css/skin-blue.css',
    'static/css/mid-lib.css?V=1.1.3',
];

if($stub=="Page" && $action=="index") {
    $css_references = array_merge($css_references,['static/css/editable.css?v=1.0',]);
}
