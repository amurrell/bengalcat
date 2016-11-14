<?php

namespace Bc\App\Queries;

use Bc\App\DbExtender;

class ArticleDbQueries extends DbExtender {

    protected $db;

    public function selectAll()
    {
        return $this->db->querySelect(
            "select * from articles;"
        );
    }

    public function selectArticleByName($articleName)
    {
        return $this->db->querySelect(
            "select * from articles where name = :article_name;", [
                'article_name' => $articleName,
            ]
        );
    }
}