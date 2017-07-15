<?php

namespace Bc\App\Core\Auth\Db;

use Bc\App\Core\DbExtender;

class SetupAuthDbQueries extends DbExtender {

    protected $db;

    public function createAuthTypesIfNotExists()
    {
        return $this->db->querySelect(
            "CREATE TABLE IF NOT EXISTS `auth_types` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;"
        );
    }
    
    public function createAuthTypeMetasIfNotExists()
    {
        return $this->db->querySelect(
            
        );
    }
    
    public function createAuthTypePermsIfNotExists()
    {
        return $this->db->querySelect(
            
        );
    }
    
    public function createAuthMetasIfNotExists()
    {
        return $this->db->querySelect(
            
        );
    }
    
    public function createAuthPermsIfNotExists()
    {
        return $this->db->querySelect(
            
        );
    }
    
    public function createAuthQueriesIfNotExists()
    {
        return $this->db->querySelect(
            
        );
    }
}