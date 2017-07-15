<?php

namespace Bc\App\Queries;

use Bc\App\Core\DbExtender;

class ArticleDbQueries extends DbExtender {

    protected $db;

    public function selectAll()
    {
        return $this->db->querySelect(
            "select * from articles;"
        );
    }

    public function selectArticleByName($data)
    {
        return $this->db->querySelect(
            "select * from articles where title = :article_title;",
            $data
        );
    }

    public function selectArticleById($data)
    {
        return $this->db->querySelect(
            "select * from articles where id = :article_id;",
            $data
        );
    }
}