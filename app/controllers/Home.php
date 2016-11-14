<?php

namespace Bc\App\Controllers;

class Home extends \Bc\App\RouteExtender {

    protected function init()
    {

        $slogan = \Bc\App\Util::getTemplateContents(SRC_DIR . 'tokenHTML/slogan-home.php');

        $this->render(SRC_DIR . 'main/home.php', null,
                array('[bc:slogan]' => $slogan));
    }
}

