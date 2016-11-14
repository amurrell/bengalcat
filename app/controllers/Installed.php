<?php

namespace Bc\App\Controllers;

class Installed extends \Bc\App\RouteExtender {

    protected function init()
    {
        $this->render(SRC_DIR . 'installed.php');
    }
}

