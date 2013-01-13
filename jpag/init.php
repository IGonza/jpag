<?php

require_once 'classes/jpag.php';




// related paths
/*define('JPAG_LOCATION', '/includes/modules/jpaginate/');
define('PLUGINS_REL', JPAG_LOCATION.'plugins/');

//absolute paths // no need to change usually
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
if (!defined('CONFIG')) define('CONFIG', ROOT.JPAG_LOCATION.'configure/');
if (!defined('JPAGINATE_CONFIG')) define('JPAGINATE_CONFIG', '');
define('JPAG_FUNCTIONS', ROOT.JPAG_LOCATION.'functions/');
define('JPAG_FORMAT_FUNCTIONS', ROOT.JPAG_LOCATION.'functions/formats/');
define('PLUGINS', ROOT.JPAG_LOCATION.'plugins/');
if (!defined('TEMPLATES')) define('TEMPLATES', ROOT.JPAG_LOCATION.'templates/');
if (!defined('JPAGINATE_TEMPLATE')) define('JPAGINATE_TEMPLATE', 'template.tpl');
*/

////////////////////////////////////////////////

/*if (!isset($jp_dbdata_conn)) $jp_dbdata_conn = $jp_dbmain_conn;

define("SERVER_FILE", $_SERVER['PHP_SELF']);
require_once(JPAG_FUNCTIONS.'general.php');
require_once(JPAG_FUNCTIONS."core.php");
*/
/*
$plugin_conf = array();

$j_load = isset($_GET['load']) ? $_GET['load']:"";
switch ($j_load) {
	case "data":
		$jpaginate_config = loadConfig(CONFIG.JPAGINATE_CONFIG);
		require_once(JPAG_FUNCTIONS."plugins.php");
		echo jpaginate_loadData();
		die();
		break;
	case "js":
		if (isset($_GET['f'])) jpaginate_loadJS($_GET['f']);
		else die("configuration file missed");
		die();
		break;
	case "pl_request":
		$jpaginate_config = loadConfig(CONFIG.JPAGINATE_CONFIG);
		require_once(JPAG_FUNCTIONS."plugins.php");
		echo jpaginate_updateData();
		die();
		break;
	default:
		$jpaginate_config = loadConfig(CONFIG.JPAGINATE_CONFIG);
		require_once(JPAG_FUNCTIONS."plugins.php");
		break;
}*/