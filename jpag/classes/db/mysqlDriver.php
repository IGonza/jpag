<?php

class mysqlDriver {

    private $_dbhost = "localhost";
    private $_dbname;
    private $_dbuser;
    private $_dbpassword;
    private $_db;

    public function _contruct($dbInfo) {

        if (isset($dbInfo['dbhost']))
            $this->_dbhost = $dbInfo['dbhost'];
        if (isset($dbInfo['dbname']))
            $this->_dbhost = $dbInfo['dbname'];
        if (isset($dbInfo['dbuser']))
            $this->_dbhost = $dbInfo['dbuser'];
        if (isset($dbInfo['dbpassword']))
            $this->_dbhost = $dbInfo['password'];
    }

    public function connect() {

        $dsn = 'mysql:host=' . $this->_dbServer . ';dbname=' . $this->_dbname;

        try {

            $this->_db = new PDO($dsn, $this->user, $password);
        } catch (PDOException $e) {

            //echo 'Connection failed: ' . $e->getMessage();
            return false;
        }

        return true;
    }

}