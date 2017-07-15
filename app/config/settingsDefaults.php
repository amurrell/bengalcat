<?php

/*
 * Version-Controlled default settings go here.
 * Create settings.php (is in .gitignore) to add to or override default settings.
 */

return [
    'defaultTheme'         => 'bengalcat',
    'timeZone'             => 'America/Los_Angeles',
    'errorRoute'           => '\Bc\App\Controllers\Example\View\Error',
    'errorJsonIdentifiers' => [
        '/api/'
    ],
    'navRenderPath'        => THEMES_DIR . 'bengalcat/src/tokenHTML/nav.php',
    'navActiveClass'       => 'bc-active',
    'navItems'             => [
        'docs'     => [
            'attributes'      => [
                'href' => '/docs/',
            ],
//            'fontawesomeIcon' => 'copy',
            'display'         => 'Docs',
            'matchingRoutePath' => '/docs/'
        ],
        'about'    => [
            'attributes'        => [
                'href' => '/about/',
            ],
//            'fontawesomeIcon'   => 'question',
            'display'           => 'About',
            'matchingRoutePath' => '/about/'
        ],
        'download' => [
            'attributes'      => [
                'href' => 'https://github.com/amurrell/bengalcat/',
            ],
            'fontawesomeIcon' => 'download',
            'display'         => 'Download',
        ],
    ],
    'cms' => [
        'displays' => [
            'docs' => ['controller' => '\Bc\App\Controllers\Example\View\Docs'],
            'doc' => ['controller' => '\Bc\App\Controllers\Example\View\Doc'],
        ]
    ],
    'admin' => [
        'theme' => 'admin',
        'apps' => [
            'auth' => [
                'controller' => '\Bc\App\Core\Auth\Admin\AuthAdmin',
            ],
            'cms' => [
                'controller' => '\Bc\App\Core\Cms\Admin\CmsAdmin',
            ]
        ]
    ]
];

