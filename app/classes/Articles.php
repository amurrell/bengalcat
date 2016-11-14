<?php

namespace Bc\App\Classes;

use Bc\App\Queries\ArticleDbQueries;
use Bc\App\RouteClass;
use Bc\App\Util;

class Articles extends RouteClass {

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

