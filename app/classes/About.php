<?php

namespace Bc\App\Classes;

class About extends \Bc\App\RouteClass {
    
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

