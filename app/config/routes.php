<?php

return [
    
    /* Custom View Controllers */
    '/' => '\Bc\App\Controllers\Example\View\Installed',
    
    /* Admin - ie cms, users, custom */
    '/(admin)/(.*)' => '\Bc\App\Core\Admin\AdminIndex',
    
    /* CMS */
    '/api/cms/[^/]*/route(/.*)' => '\Bc\App\Core\Cms\CmsIndex',
    '(/.*)' => '\Bc\App\Core\Cms\CmsIndex',
    
];