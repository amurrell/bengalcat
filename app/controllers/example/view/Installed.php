<?php

namespace Bc\App\Controllers\Example\View;

use Bc\App\RouteExtenders\ExampleRouteExtender;

class Installed extends ExampleRouteExtender {

    /*
     * Override this method to change paths to header, head, footer
     * 
     * protected function setTemplatePaths()
     *  {
     *      $this->setHeader($this->getThemePart('/src/templates/header.php'))
     *           ->setHead($this->getThemePart('/src/templates/head.php'))
     *           ->setFooter($this->getThemePart('/src/templates/footer.php'));
     *  }
     * 
     */
    
    
    protected function init()
    {
        /* To use a different theme: 
         *
         * - Make sure folder exists in /html/themes
         * - This will allow your header/footer to load properly...
         * 
         * $this->setTheme('yourtheme');
         * 
         * - or if you want to manually control it, override the setTemplatePaths
         * and use this for paths: 
         * 
         * $this->bc->getThemePath($theme, $append);
         */
        
        
        // Uncomment this to see how an additional menu item can be added per route.
//        $this->nav->addTempItem(
//            'test_menu_add_item',
//            [
//                'href' => 'http://www.google.com/',
//            ],
//            '', // icon
//            'Added Menu Item',
//            null,
//            'about' // Put this item after 'about' (look at default settings)
//        );
        
        // We also need an ASSETS_DIR for images / favicon (head, header)
        // But to avoid doing it in every view, we can add it to the ExampleRouteExtender.
        // define('ASSETS_DIR', $this->getThemePart('/assets/build/img/'));
        
        $this->render(
            $this->getThemePart('/src/main/installed.php'),
            null, 
            [
                '[:nav]' => $this->nav->getNav(true)
            ]
        );
    }
}