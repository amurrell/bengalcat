<?php

namespace Bc\App\Controllers;

class Docs extends \Bc\App\RouteExtender {

    protected function init()
    {

        Util::trigger404($this->bc);
    }
}

