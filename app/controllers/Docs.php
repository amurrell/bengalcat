<?php

namespace Bc\App\Controllers;

use Bc\App\RouteExtenders\ExtendedRouteExtender;
use Bc\App\Util;

class Docs extends ExtendedRouteExtender {

    protected function init()
    {
        Util::trigger404($this->bc);
    }
}

