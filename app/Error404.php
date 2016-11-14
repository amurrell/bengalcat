<?php

namespace Bc\App;

class Error404 extends \Bc\App\RouteExtender {

    protected function init()
    {

        $slogan = \Bc\App\Util::getTemplateContents(SRC_DIR . 'tokenHTML/slogan-404.php', $data);

        $this->render(SRC_DIR . '404.php', null,
                array('[bc:slogan]' => $slogan));
    }
}

