<?php

/*
 * File: class.jpag.php
 * Version 1.0
 * Author: IGonza
 *
 */

class jpag {

    private $_config;
    private $_debug = false;
    
    public function get_config() {
        return $this->_config;
    }

    public function set_config($_config) {
        $this->_config = $_config;
    }

    public function get_debug() {
        return $this->_debug;
    }

    public function set_debug($_debug) {
        $this->_debug = $_debug;
    }


}