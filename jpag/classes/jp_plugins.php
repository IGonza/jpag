<?php

class jp_plugins {
    
    private $_plugins = array();
    private $_location;
   
    
    public function __construct() {
        
        $this->_location = dirname( __FILE__ ). "/../plugins/";
        
    }
    
    public function load($config) {
             
        $pluginNum = count($config);
                
        for ($i=0; $i<$pluginNum; $i++) {
                
            if (isset($config[$i]["name"]) && !empty($config[$i]["name"])) {
                
                $pluginName = $config[$i]["name"];
                
                if (is_file($this->_location.$pluginName."/".$pluginName.".php")) {
                    
                    require_once $this->_location.$pluginName."/".$pluginName.".php";
                    
                    if (class_exists($pluginName)) {
                        
                        array_push($this->_plugins, new $pluginName($config[$i]));
                        
                    }
                    
                }
                
            }
                
        }
       
    }
    
    public function replaceJsShortCode($shortCode) {
        
        $pluginNum = count($this->_plugins);
        $shortCodeContent = "";
                
        for ($i=0; $i<$pluginNum; $i++) {
            
            $shortCodeContent .= $this->_plugins[$i]->replaceJsShortCode($shortCode);
                
        }
        
        return $shortCodeContent;
        
    }
    
    
}