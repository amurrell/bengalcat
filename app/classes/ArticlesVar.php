<?php

namespace Bc\App\Classes;

class ArticlesVar extends Articles {
    
    private $db;
    
    protected function init()
    {
        $data = array();
        $data['article'] = $this->findArticle();
        
        $this->render(SRC_DIR . 'main/articles.php', $data, array('[bc:slogan]' => 'Articles are cool.'));
    }
    
    protected function findArticle()
    {
        // Do not store these values in code.
        $this->db = new \Bc\App\Db(
                getenv('BC_DB_NAME'),
                getenv('BC_DB_USER'),
                getenv('BC_DB_PASS')
                );
        
        $article = $this->db->query("select * from articles where variant = :variant;", 
                array('variant' => $this->getVariant())
            );
        
        // If no article found, 404.
        if (empty($article)) {
            \Bc\App\Util::trigger404();
        }
        
        return $article;
    }
}

