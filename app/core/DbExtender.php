<?php

namespace Bc\App\Core;

abstract class DbExtender {

    protected $connections;
    protected $db;

    public function __construct($dbName) 
    {
        $this->connections = include APP_DIR . 'config/connections.php';

        $db = $this->getDbConnectionData($dbName);

        $this->db = new Db(
            !empty($db->name) ? $db->name : $dbName,
            $db->user,
            $db->pass,
            $db->host,
            !empty($db->port) ? $db->port : '3306'
        );
    }

    protected function getDbConnectionData($dbName)
    {
        $db = isset($this->connections[$dbName])
            ? (object) $this->connections[$dbName]
            : [];

        if (empty($db)) {
            Util::triggerError(
                array(
                    'success' => false,
                    'error_code' => 501,
                    'message' => 'This route has not been fully implemented. Check Database Connection Exists.'
                )
            );
        }

        $db->host = (empty($db->host)) ? 'localhost' : $db->host;

        return $db;
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
        return $this;
    }

    public function commit()
    {
        $this->db->commit();
        return $this;
    }

    public function rollBack()
    {
        $this->db->rollBack();
        return $this;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

}