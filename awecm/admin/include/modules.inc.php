<?php
if( !defined('__URBAN_OFFICE__') ) exit;

$modules_list = [

  'Page' => [
    'tab'         => 'Pages',
    'module'      => ['Page'],
    'parent_icon' => 'fa fa-book',
    'role'        => 'admin',
    'child'       => [
                        [ 'tab'  => 'All Pages',
                          'icon' => 'fa fa-circle-o',
                          'link' => './?module=Page&action=index'
                        ],

                        [ 'tab'  => 'Add New',
                          'icon' => 'fa fa-circle-o',
                          'link' => './?module=Page&action=EditView'
                        ]
                     ]
  ],

  'Property' => [
    'tab'         => 'Properties',
    'module'      => ['Property'],
    'parent_icon' => 'fa fa-suitcase',
    'role'        => 'admin',
    'link'       => './?module=Property&action=index',
  ],

  'Press' => [
    'tab'         => 'Press',
    'module'      => ['Press'],
    'parent_icon' => 'fa fa-newspaper-o',
    'role'        => 'admin',
  'link'       => './?module=Press&action=index',
  ],

  'Blog' => [
    'tab'         => 'Blog',
    'module'      => ['Blog'],
    'parent_icon' => 'fa fa-rss',
    'role'        => 'admin',
  'link'       => './?module=Blog&action=index',
  ],

  'Inquiry' => [
    'tab'         => 'Inquiries',
    'module'      => ['Inquiry'],
    'parent_icon' => 'fa fa-envelope',
    'role'        => 'admin',
    'link'        => './?module=Enquiry&action=index'
  ],

  'FAQ' => [
    'tab'         => 'FAQ',
    'module'      => ['FAQ'],
    'parent_icon' => 'fa fa-comments',
    'role'        => 'admin',
    'link'        => './?module=FAQ&action=index'
  ],
  
  'OurTeam' => [
    'tab'         => 'Our Team',
    'module'      => ['Our Team'],
    'parent_icon' => 'fa fa-group',
    'role'        => 'admin',
  'link'       => './?module=OurTeam&action=index',
  ],

  'Media' => [
    'tab'         => 'Media',
    'module'      => ['Media'],
    'parent_icon' => 'fa fa-picture-o',
    'role'        => 'admin',
    'link'        => './?module=Media&action=index'
  ],

  'Widget' => [
    'tab'         => 'Widgets',
    'module'      => ['Widget'],
    'parent_icon' => 'fa fa-briefcase',
    'role'        => 'admin',
    'link'        => './?module=Widget&action=index'
  ],

  'Menus' => [
    'tab'         => 'Menus',
    'module'      => ['Menu'],
    'parent_icon' => 'fa fa-bars',
    'role'        => 'admin',
    'link'        => './?module=Menu&action=index'
  ],


  'Settings' => [
    'tab'         => 'Settings',
    'module'      => ['Settings'],
    'parent_icon' => 'fa fa-cog',
    'role'        => 'admin',
    'link'        => './?module=Settings&action=EditView'

  ],

  'System Users' => [
    'tab'         => 'System Users',
    'module'      => ['SystemUser'],
    'parent_icon' => 'fa fa-user',
    'link'        => './?module=SystemUser&action=index',
    'role'        => 'admin',
  ],

  'Tools'  => [
    'tab'         => 'Tools',
    'module'      => ['Redirection'],
    'parent_icon' => 'fa fa-cog',
    'role'        => 'admin',
    'child'       => [
                      
                        [ 'tab'  => 'Purge Cache',
                          'icon' => 'fa fa-trash-o',
                          'link' => './?module=Settings&action=PurgeCache'
                        ],
                        [ 'tab'  => 'Minify Static Files',
                          'icon' => 'fa fa-compress',
                          'link' => './?module=Settings&action=Minify'
                        ],
                          [ 'tab'  => 'Redirections',
                          'icon' => 'fa fa-circle-o',
                          'link' => './?module=Redirection&action=index'
                        ],
                        
                      ]

  ],

];

$css_references = [
  'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css',
  'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
  '../static/css/select2.min.css',
  '../static/css/AdminLTE.min.css',
  '../static/css/skin-blue.css',
  '../static/css/aw-admin.css',
  '../static/css/editable.css',
  '../static/javascript/datepicker/datepicker3.css',
  '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css'
];

$js_references = [
  '../static/javascript/jquery-2.2.3.min.js',
  '../static/javascript/bootstrap.min.js',
  '../static/javascript/app.min.js',
  '../static/javascript/validate/jquery.validate.min.js',
  '../static/javascript/jquery.slimscroll.min.js',
  '../static/javascript/jquery.bootstrap-growl.min.js',
  '../static/javascript/editor/tiny/tinymce.min.js',
  '../static/javascript/dropzone.js',
  '../static/javascript/CustomValiation.js',
  '../static/javascript/jquery.are-you-sure.min.js',
  '../static/javascript/select2.full.min.js'

];

$js_references = [
  'Page'        => array_merge($js_references, ['../static/javascript/editable.js', '../static/javascript/page.js']),
  'Property'     => array_merge($js_references, ['../static/javascript/editable.js', '../static/javascript/property.js']),
  'Press'        => array_merge($js_references, ['../static/javascript/editable.js', '../static/javascript/press.js', '../static/javascript/datepicker/bootstrap-datepicker.js',]),
  'Blog'        => array_merge($js_references, ['../static/javascript/editable.js', '../static/javascript/blog.js', '../static/javascript/datepicker/bootstrap-datepicker.js',]),
  'FAQ'          => array_merge($js_references, ['../static/javascript/editable.js', '../static/javascript/faq.js']),
  'OurTeam'        => array_merge($js_references, ['../static/javascript/editable.js', '../static/javascript/OurTeam.js', '../static/javascript/datepicker/bootstrap-datepicker.js',]),
  'Enquiry'     => array_merge($js_references, ['../static/javascript/datepicker/bootstrap-datepicker.js', '../static/javascript/enquiry.js']),
  'SystemUser'  => array_merge($js_references, ['../static/javascript/SystemUserEdit.js']),
  'Media'       => array_merge($js_references, ['../static/javascript/media-library.js', '../static/javascript/select2.full.min.js']),

  'Settings'    => array_merge($js_references, ['../static/javascript/editable.js', '../static/javascript/settings.js']),
  'Menu'        => array_merge($js_references, ['../static/javascript/select2.full.min.js', '//code.jquery.com/ui/1.10.4/jquery-ui.min.js', '../static/javascript/menu/jquery.mjs.nestedSortable.js', '../static/javascript/menu.js']),
  'Widget'      => array_merge($js_references, ['../static/javascript/widget.js']),
  'Redirection' => array_merge($js_references, ['../static/javascript/editable.js','../static/javascript/redirection.js']),
];

