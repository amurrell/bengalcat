<?php

/**
 * Db Class - MySQL
 *
 * @note Do not want to use mysql? You'll probably need to write your own Db Class
 * @note But, I believe in you! And the MIT License says GO FOR IT.
 */

namespace Bc\App;

use PDO;
use PDOException;

class Db {

    private $db;
    private $dbname;
    private $user;
    private $password;
    private $host;
    private $charset;

    /**
     * Do not store DB info in the code / version control!
     */
    public function __construct(
            $dbname,
            $user,
            $password,
            $host = 'localhost',
            $charset = 'utf8mb4'
        ) {

        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->charset = $charset;

        return $this;
    }

    protected function openConn()
    {
        $dbString = "mysql:host={$this->host};"
        . "dbname={$this->dbname};"
        . "charset={$this->charset}";

        try {
            $this->db = new PDO($dbString, "{$this->user}", "{$this->password}");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            Util::triggerError(
                array(
                    'success' => false,
                    'error_code' => 500,
                    'message' => 'Check Database Permissions or SQL logs.'
                )
            );
        }

        return $this;
    }

    /**
     * Close db connection
     * @return Db
     */
    public function closeConn()
    {
        // There is no db connection to close
        if (!isset($this->db)) {
            return $this;
        }

        // Close the db connection
        $this->db = null;
        unset($this->db);

        return $this;
    }

    public function beginTransaction()
    {
        // Open connection automatically
        if (!isset($this->db)) {
            $this->openConn();
        }
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }

    public function queryInsert(
            $string,
            $params = array()
        ) {
        $this->querySelect($string, $params, null, null);
    }

    public function queryUpdate(
            $string,
            $params = array()
        ) {
        $this->querySelect($string, $params, null, null);
    }

    public function querySelect(
            $string,
            $params = array(),
            $statementMethod = 'fetchAll',
            $methodArgs = PDO::FETCH_ASSOC
        ) {
        // Open connection automatically
        if (!isset($this->db)) {
            $this->openConn();
        }

        try {

            // Use prepared statements only if needed
            if (empty($params)) {
                $q = $this->db->query($string);
            }
            else {
                $q = $this->db->prepare($string);
                $q->execute($params);
            }

            // If not empty statement method, apply it.
            /** @note if planning to use multiple methods, pass nothing. */
            if (!empty($statementMethod)) {

                $data = (empty($methodArgs)) ?
                        $q->{$statementMethod}() :
                        $q->{$statementMethod}($methodArgs);


                if (!$this->db->inTransaction()) {
                    $this->closeConn();
                }

                return $data;
            }
            else {
                /** @note return statement (remember to close conn) */
                return $q;
            }

        } catch (PDOException $e) {

            if ($this->db->inTransaction()) {
                $this->rollBack();
                $this->closeConn();
            }

            Util::triggerError(array(
               'success' => false,
               'error_code' => (int) $e->getCode(),
               'message' => $e->getMessage()
            ));
        }
    }
}

