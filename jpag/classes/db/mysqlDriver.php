<?php

class mysqlDriver {

    private $_dbhost = "localhost";
    private $_dbname;
    private $_dbuser;
    private $_dbpassword;
    private $_db;

    public function __construct($dbInfo) {

        if (isset($dbInfo['dbhost']))
            $this->_dbhost = $dbInfo['dbhost'];
        if (isset($dbInfo['dbname']))
            $this->_dbname = $dbInfo['dbname'];
        if (isset($dbInfo['dbuser']))
            $this->_dbuser = $dbInfo['dbuser'];
        if (isset($dbInfo['dbpassword']))
            $this->_dbpassword = $dbInfo['dbpassword'];
    }

    public function connect() {

        $dsn = 'mysql:host=' . $this->_dbhost . ';dbname=' . $this->_dbname;

        try {

            $this->_db = new PDO($dsn, $this->_dbuser, $this->_dbpassword);
            
        } catch (PDOException $e) {

            echo 'Connection failed: ' . $e->getMessage();
            return false;
        }

        return true;
    }

}