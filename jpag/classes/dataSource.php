<?php


class dataSource {
    
    private $_dataSource;
    
    public function __construct($_source) {
        
        switch ($_source) {
            
            case "db":
                require_once 'db/db.php';
                $this->_dataSource = new dbDriver();
                break;
            case "file":
                //require_once 'file/file.php';
                //$this->_dataSource = new fileDriver();
                break;
            default: 
                return 0;
            
        }
    }
    
    public function setConnection($string) {
        
        return $this->_dataSource->connect($string);
        
        
    }
    
    
    
}