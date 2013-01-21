<?php


class dataSource {
    
    private $_dataSource;
    //private $_connectionString;
    
    
    
    public function _contruct($_source) {
        
        switch ($_source) {
            
            case "db":
                $this->_dataSource = new dbDriver();
                break;
            case "file":
                $this->_dataSource = new fileDriver();
            default: 
                return 0;
            
        }
    }
    
    public function setConnection($string) {
        
        return $this->_dataSource->connect($string);
        
        
    }
    
    
    
}