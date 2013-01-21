<?php

class dbDriver {
    
    
    private $_dataSource = 'mysql';
    private $_dbServer = 'localhost';
    private $_dbUsername;
    private $_dbPassword;
    private $_dbName;
    
    private $_db;
    
    public function _contruct() {

        
        
    }
    
    public function connect($dbInfo) {
        
        if (isset($dataInfo['dbms'])) $this->_dataSource = $dataInfo['dbms'];
        
        switch ($this->_dataSource) {
            
            case 'pgsql':
                return false;
                break;
            case 'mysql':
            default:
                $this->_db = new mysqlDriver($dbInfo);
                return $this->_db->connect();
  
        }
        
    }
    
    
    
}