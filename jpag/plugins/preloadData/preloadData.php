<?php

class preloadData {
    
    private $_settings = array();
    
    public function __construct($settings = array()) {
        
        $this->_settings = $settings;
           
    }
    
    public function replaceJsShortCode($jsShortCode) {
        
        $jsShortCodeContent = "/* preloadData plugin */";
        switch ($jsShortCode) {
            
            case "{*jp_ready*}": 
                if ($this->_settings['active'] == true) 
                    $jsShortCodeContent .= "\n"."loadPaginationTable(0);";          
                else
                    $jsShortCodeContent .= "\n".'$("#status_indicator").attr("style", "visibility:hidden");';
                break;
           default:
               break;

        }
        
        return $jsShortCodeContent;
        
        
    }
    
}