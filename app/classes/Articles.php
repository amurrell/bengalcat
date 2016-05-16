<?php

namespace Bc\App\Classes;

class Articles extends \Bc\App\RouteClass {
    
    protected function init()
    {
        /** @note Example of custom head, header, footer */
        $this->setHead(SRC_DIR . 'templates/head-articles.php');
        $this->setHeader(SRC_DIR . 'templates/header-minimal.php');
        $this->setFooter(SRC_DIR . 'templates/footer-with-cta.php');
        $this->render(SRC_DIR . 'articles.php');
    }
}

