<?php

/*
 * File: class.jpag.php
 * Version 1.0
 * Author: IGonza
 *
 */

class Jpag {
    
    const JQUERY_LOCATION = 'http://code.jquery.com/jquery-latest.min.js';
        
    private $_debug = false;
    private $_configFile;
    private $_dataSource = 'mysql';
    private $_dbServer = 'localhost';
    private $_dbUsername;
    private $_dbPassword;
    private $_dbName;
    private $_db;
    private $_errorMsg;

    public function _contruct() {

    }

    public function load() {
        
        $dsn = $this->_dataSource . ':dbname=' .
               $this->_dbName . ';host=' .
               $this->_dbServer;
        
        try {
            $this->_db = new PDO($dsn, $this->_dbUsername, $this->_dbPassword);
        }
	catch (PDOException $e) {
	    $this->_errorMsg = $e->getMessage();
            return false;
        }
        
        return true;
        
    }
    
    public function header() {
        
    }
    
    public function data() {
        
    }
    
    
    public function get_debug() {
        return $this->_debug;
    }

    public function set_debug($_debug) {
        $this->_debug = $_debug;
    }
    
    public function get_configFile() {
        return $this->_configFile;
    }

    public function set_configFile($_configFile) {
        $this->_configFile = $_configFile;
    }
    
    public function get_dataSource() {
        return $this->_dataSource;
    }

    public function set_dataSource($_dataSource) {
        $this->_dataSource = $_dataSource;
    }
    
    public function get_dbServer() {
        return $this->_dbServer;
    }

    public function set_dbServer($_dbServer) {
        $this->_dbServer = $_dbServer;
    }

    public function get_dbUsername() {
        return $this->_dbUsername;
    }

    public function set_dbUsername($_dbUsername) {
        $this->_dbUsername = $_dbUsername;
    }

    public function get_dbPassword() {
        return $this->_dbPassword;
    }

    public function set_dbPassword($_dbPassword) {
        $this->_dbPassword = $_dbPassword;
    }
    
    public function get_dbName() {
        return $this->_dbName;
    }

    public function set_dbName($_dbName) {
        $this->_dbName = $_dbName;
    }
    
    public function get_errorMsg() {
        return $this->_errorMsg;
    }


}