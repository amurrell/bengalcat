<?php

namespace Bc\App\Classes;

class Docs extends \Bc\App\RouteClass {
    
    protected function init()
    {
        
        Util::trigger404($this->bc);
    }
}

