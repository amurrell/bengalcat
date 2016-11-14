<?php

namespace Bc\App;

abstract class DbExtender {

    protected $connections;
    protected $db;

    public function __construct($dbName) {
        $this->connections = include_once APP_DIR . 'config/connections.php';

        $db = $this->getDbConnectionData($dbName);

        $this->db = new Db(
            $dbName,
            $db->user,
            $db->pass,
            $db->host
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

}

