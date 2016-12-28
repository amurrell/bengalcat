<?php

namespace Bc\App\Controllers;

use Bc\App\RouteExtenders\ExtendedRouteExtender;

class Installed extends ExtendedRouteExtender {

    protected function init()
    {

        $this->nav->addTempItem(
            'test_menu_add_item',
            [
                'href' => '/articles/',
            ],
            'list', // icon
            'Test Menu Item',
            '/articles/',
            'about' // Put this item after 'about' (look at Utils\NavUtils)
        );

        $this->render(SRC_DIR . 'installed.php', null, [
            '[bc:nav]' => $this->nav->getNav(true)
        ] );
    }
}

