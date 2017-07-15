<?php

namespace Bc\App\Controllers\Example\View;

use Bc\App\RouteExtenders\ExampleRouteExtender;

class Home extends ExampleRouteExtender {
    
    protected function init()
    {
        $this->render(
            $this->getThemePart('/src/main/home.php'),
            null, 
            [
                '[:nav]' => $this->nav->getNav(true),
                '[:hero text]' => $this->getThemePartContents('/src/tokenHTML/hero/text-home.php')
            ]
        );
    }
}