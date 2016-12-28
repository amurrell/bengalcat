<?php

namespace Bc\App\Controllers;

use Bc\App\RouteExtenders\SiteRouteExtender;
use Bc\App\Util;

class Articles extends SiteRouteExtender {

    protected function init()
    {
        $this->addQuery(
            'articles',
            'articleQueries',
            'selectAll'
        );

        $this->prepareData();
        $this->prepareTokens();
        $this->render(SRC_DIR . 'main/articles.php', $this->data, $this->tokens);
    }

    protected function prepareTokens()
    {
        $this->tokens = [
            '[bc:slogan]' => 'Testing Articles',
            '[bc:articles list]' => Util::getTemplateContents(
                SRC_DIR . 'tokenHTML/articles-list.php',
                $this->data
            ),
            '[bc:base link]' => '/articles/',
        ];

        return $this;
    }
}
