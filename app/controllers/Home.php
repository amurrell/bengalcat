<?php

namespace Bc\App\Controllers;

use Bc\App\RouteExtenders\ExtendedRouteExtender;
use Bc\App\Util;

class Home extends ExtendedRouteExtender {

    protected function init()
    {

        $slogan = Util::getTemplateContents(SRC_DIR . 'tokenHTML/slogan-home.php');

        $this->render(SRC_DIR . 'main/home.php', null,
                array('[bc:slogan]' => $slogan));
    }
}

