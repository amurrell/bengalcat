<?php

namespace Bc\App\Classes;

class Home extends \Bc\App\RouteClass {

    protected function init()
    {

        $slogan = \Bc\App\Util::getTemplateContents(SRC_DIR . 'tokenHTML/slogan-home.php');

        $this->render(SRC_DIR . 'main/home.php', null,
                array('[bc:slogan]' => $slogan));
    }
}

