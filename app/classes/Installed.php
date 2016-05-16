<?php

namespace Bc\App\Classes;

class Installed extends \Bc\App\RouteClass {
    
    protected function init()
    {
        $this->render(SRC_DIR . 'installed.php');
    }
}

