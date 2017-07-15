<?php

namespace Bc\App\Core\Cms\Db;

use Bc\App\Core\DbExtender;

class SetupCmsDbQueries extends DbExtender {

    protected $db;
    
    public function setup()
    {
        $this->createRoutesIfNotExists();
        $this->createRoutesTypesIfNotExists();
        $this->createRoutesTypeMetasIfNotExists();
        
        $this->createRouteMetasIfNotExists();
        $this->createRouteRelatedQueriesIfNotExists();
        
        $this->createRouteQueriesIfNotExists();
    }

    public function createRoutesIfNotExists()
    {
        return $this->db->querySelect(
            "CREATE TABLE IF NOT EXISTS `routes` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `route` varchar(400) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;"
        );
    }
    
    public function createRoutesTypesIfNotExists()
    {
        return $this->db->querySelect(
           
        );
    }
    
    public function createRoutesTypeMetasIfNotExists()
    {
        return $this->db->querySelect(
           
        );
    }
    
    public function createRouteMetasIfNotExists()
    {
        return $this->db->querySelect(
           
        );
    }
    
    public function createRouteRelatedQueriesIfNotExists()
    {
        return $this->db->querySelect(
           
        );
    }
    
    public function createRouteQueriesIfNotExists()
    {
        return $this->db->querySelect(
           
        );
    }
}