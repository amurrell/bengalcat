<?php

namespace Bc\App\Controllers;

use Bc\App\Queries\ArticleDbQueries;
use Bc\App\RouteExtender;
use Bc\App\Util;

class Articles extends RouteExtender {

    protected $data;
    protected $articleQueries;

    protected function init()
    {
        $this->articleQueries = new ArticleDbQueries('database_name');
        $this->findArticles();

        $this->render(SRC_DIR . 'main/articles.php', $this->data, array('[bc:slogan]' => 'Articles are cool.'));
    }

    protected function findArticles()
    {
        $this->data = $this->articleQueries->selectAll();

        // If no article found, 404.
        if (empty($this->data)) {
            Util::trigger404($this->bc);
        }
    }
}

