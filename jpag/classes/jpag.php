<?php

/*
 * File: class.jpag.php
 * Version 1.0
 * Author: IGonza
 *
 */

class Jpag {

    const JQUERY_LOCATION = 'http://code.jquery.com/jquery-latest.min.js';

    private $_jpLocation;
    private $_jpWebLocation;
    private $_debug = false;
    private $_errorMsg;
    private $_configType = 'json';
    private $_configString;
    private $_sourceType;
    private $_dataSource;
    private $_connectionData = array();
    private $_config = array();
    private $_jsContent;
    private $_jsShortCodes = array("{*jp_ready*}", "", "");
    private $_plugins;

    public function __construct() {

        $this->_jpLocation = dirname(__FILE__) . "/../";
        $this->_plugins = new jp_plugins();
    }

    public function load($sourceType, $connectionArray) {

        $this->_connectionData = $connectionArray;
        $this->_sourceType = $sourceType;
        $this->_dataSource = new dataSource($this->_sourceType);

        if (!$this->_dataSource) {
            $this->_errorMsg = "Unknown data source";
            return false;
        }

        // connect to data source (db, file?)
        $this->_dataSource->setConnection($this->_connectionData);

        // parse configuration to array
        $this->parseConfigFile();
        $this->loadPlugins();

        $jp_load = isset($_GET['jp_load']) ? $_GET['jp_load'] : "";

        // check what kind of requuest we've got
        switch ($jp_load) {
            // update data on the page
            case "data":
                echo $this->loadData();
                die();
                break;
            // initial load javascript 
            case "js":
                header('Content-Type: application/javascript');
                $this->buildJS();
                echo $this->_jsContent;
                die();
                break;
            // request from a plugin
            case "pl":
                echo $this->pluginRequest();
                die();
                break;
            default:
                break;
        }

        return true;
    }

    private function loadData() {

        return "";
    }

    private function pluginRequest() {

        return "";
    }

    private function buildJS() {

        $this->_jsContent = file_get_contents($this->_jpLocation . "js/jpaginate.js");

        $this->replaceJsShortCodes();
    }

    private function replaceJsShortCodes() {

        foreach ($this->_jsShortCodes as $jsShortCode) {
            $shortCodeContent = $this->_plugins->replaceJsShortCode($jsShortCode);
            $this->_jsContent = str_replace($jsShortCode, $shortCodeContent, $this->_jsContent);
        }
    }

    private function loadPlugins() {

        if (isset($this->_config["plugins"])) {

            $this->_plugins->load($this->_config["plugins"]);
        }
    }

    private function parseConfigFile() {

        switch ($this->_configType) {

            case 'xml':
                $this->_errorMsg = "XML configuration file is not supported";
                break;
            case 'json':
            default:
                require_once 'parser/jp_json.php';
                $this->_config = jp_json::toArray($this->_configString);
                break;
        }

        if ($this->_config == NULL)
            $this->_errorMsg = "Cannot read configuration data";
        else {

            // set default values
            // include jquery or not
            if (!isset($this->_config['loadJQuery']) || $this->_config['loadJQuery'] == "yes" || $this->_config['loadJQuery'] == 1) {
                $this->_config['loadJQuery'] = TRUE;
            }
            // set default template
            if (!isset($this->_config['template']) 
                 || empty($this->_config['template']) 
                 || !is_file($this->_config['template'])) {
                $this->_config['template'] = "default";
            }
            // set jpag web location (needed for template images!!!  REDO !!!)
            if (!isset($this->_config['jpWebLocation']) 
                 || empty($this->_config['jpWebLocation'])) {
                $this->config['jpWebLocation'] = "/";
            }
            
        }
    }

    public function header() {

        $header = "";

        if ($this->_config['loadJQuery']) {

            $header .= "<script type=\"text/javascript\" src=\"" . self::JQUERY_LOCATION . "\"></script>\n";
        }

        $js_src = $_SERVER['PHP_SELF'] . "?jp_load=js";
        if (!empty($_SERVER['QUERY_STRING']))
            $js_src .= "&" . $_SERVER['QUERY_STRING'];

        $header .= "<script type=\"text/javascript\" src=\"" . $js_src . "\"></script>\n";

        //$header .= $this->plugins->header();

        return $header;
    }

    public function data() {

        $tpl = file_get_contents($this->_jpLocation. "templates/" . $this->_config["template"] . "/template.tpl");
        $tpl = str_replace("{*JPAG_IMAGES*}", $this->_jpWebLocation. "jpag/templates/" . $this->_config["template"] . "/images/", $tpl);
        $tpl = str_replace("{*JPAG_ERROR*}", $this->_errorMsg, $tpl);
        
        return $tpl;
    }

    /*
     * Define Setters and Getters for private variables
     * 
     * 
     */

    public function get_debug() {
        return $this->_debug;
    }

    public function set_debug($_debug) {
        $this->_debug = $_debug;
    }

    public function get_configString() {
        return $this->_configString;
    }

    public function set_configString($_configFile) {
        $this->_configString = $_configFile;
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

    public function get_configType() {
        return $this->_configType;
    }

    public function set_configType($_configType) {
        $this->_configType = $_configType;
    }

}