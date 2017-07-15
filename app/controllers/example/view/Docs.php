<?php

namespace Bc\App\Controllers\Example\View;

use Bc\App\Core\RouteExtenders\ExtendedRouteExtender;
use Bc\App\Core\Util;

class Docs extends ExtendedRouteExtender {

    protected function init()
    {
        echo "Docs!";
        exit();
    }
}

