<?php

namespace Bc\App\Core;

class ErrorDefault extends \Bc\App\Core\RouteExtender {

    protected function init()
    {
        $slogan = \Bc\App\Core\Util::getTemplateContents(
            $this->bc,
            $this->getThemePart('/src/tokenHTML/hero.php')
        );

        $this->render(SRC_DIR . '404.php', null,
            [
                '[:hero]' => $slogan,
                '[:hero custom text]' => '404 Not Found',
            ]
        );
    }
}

