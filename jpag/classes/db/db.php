<?php

class dbDriver {
    
    
    private $_dataSource = 'mysql';
    //private $_dbServer = 'localhost';
    //private $_dbUsername;
    //private $_dbPassword;
    //private $_dbName;
    
    private $_db;
    
    public function __construct() {

        require_once 'driverAbstract.php';
        
    }
    
    public function connect($dbInfo) {
        
        if (isset($dbInfo['dbms'])) $this->_dataSource = $dbInfo['dbms'];
        
        switch ($this->_dataSource) {
            
            case 'pgsql':
                return false;
                break;
            case 'mysql':
            default:
                require_once 'mysqlDriver.php';
                $this->_db = new mysqlDriver($dbInfo);
                return $this->_db->connect();
  
        }
        
    }
    
    
    
}