<?php

namespace Bc\App\Controllers;

use Bc\App\RouteExtenders\ExtendedRouteExtender;

class About extends ExtendedRouteExtender {

    protected function init()
    {

        /** @note Example of using a token */
        /** @note Without this, see app/config/tokenDefaults.php */
        $slogan = 'Very little abstraction. No community. '
                . 'Hardly any docs. <i>On purpose.</i>';

        $this->render(SRC_DIR . 'main/about.php', null,
                array('[bc:slogan]' => $slogan));
    }
}

