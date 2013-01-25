<?php


class jp_json {
    
    public static function toArray($string) {
        
        return json_decode($string);
        
    }
    
}