<?php

namespace Bc\App\Classes;

use Bc\App\Queries\ArticleDbQueries;

class ArticlesVar extends Articles {

    protected $data;
    protected $articleQueries;

    protected function init()
    {
        $this->articleQueries = new ArticleDbQueries('database_name');
        $this->findArticle();

        $this->render(SRC_DIR . 'main/article.php', $this->data, array('[bc:slogan]' => 'Articles are cool.'));
    }

    protected function findArticle()
    {
        $this->data = $this->articleQueries->selectArticleByName(
            $this->getVariant()
        );

        // If no article found, 404.
        if (empty($this->data)) {
            \Bc\App\Util::trigger404($this->bc);
        }
    }
}

