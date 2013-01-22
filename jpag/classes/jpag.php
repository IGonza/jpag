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
    private $_errorMsg;
    
    private $_configFile;
    
    private $_sourceType;
    private $_dataSource;
    private $_connectionData = array();

    public function __construct() {

    }

    public function load($sourceType,$connectionArray) {
          
        $this->_connectionData = $connectionArray;
        $this->_sourceType = $sourceType;
        
        $this->_dataSource = new dataSource($this->_sourceType);
        
        if (!$this->_dataSource) {
            $this->_errorMsg = "Unknown data source";
            return false;
        }
        
        return $this->_dataSource->setConnection($this->_connectionData);
        
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
        return $this->_db->get_dataSource();
    }

    public function set_dataSource($_dataSource) {
        
        $this->_db->set_dataSource($_dataSource);
            
    }
    
    public function get_dbServer() {
        return $this->_db->get_dbServer();
    }

    public function set_dbServer($_dbServer) {
        $this->_db->set_dbServer($_dbServer);
    }

    public function get_dbUsername() {
        return $this->_db->get_dbUsername();
    }

    public function set_dbUsername($_dbUsername) {
        $this->_db->set_dbUsername($_dbUsername);
    }

    public function get_dbPassword() {
        return $this->_db->get_dbPassword();
    }

    public function set_dbPassword($_dbPassword) {
        $this->_db->set_dbPassword($_dbPassword);
    }
    
    public function get_dbName() {
        return $this->_db->get_dbName();
    }

    public function set_dbName($_dbName) {
        $this->_db->set_dbName($_dbName);
    }
    
    public function get_errorMsg() {
        return $this->_errorMsg;
    }


}