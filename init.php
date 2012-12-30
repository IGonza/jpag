<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

//define('CHARSET', 'utf-8');
//define('TITLE', 'Jpagination v0.20');

/////////////////////// to change /////////////////////////////////

// main db connection:
$jp_dbmain_conn = mysql_connect(CORE_HOST,CORE_USER,CORE_PASS, true) or die(mysql_error());
mysql_query("SET CHARACTER SET 'utf8'", $jp_dbmain_conn) or die(mysql_error());
mysql_query("SET NAMES 'utf8'", $jp_dbmain_conn) or die(mysql_error()); 


define('JPAG_JQUERY', 'http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js'); 

// related paths
define('JPAG_LOCATION', '/includes/modules/jpaginate/');
define('JPAG_CSS', JPAG_LOCATION.'jpaginate.css');
define('JPAG_IMAGES', JPAG_LOCATION.'images/');
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


//define('JPAG_FUNCTIONS_RELATIVE', JPAG_LOCATION.'functions/'); // where do we use it?

////////////////////////////////////////////////

mysql_select_db(CORE_DB, $jp_dbmain_conn) or die(mysql_error());

if (!isset($jp_dbdata_conn)) $jp_dbdata_conn = $jp_dbmain_conn;

define("SERVER_FILE", $_SERVER['PHP_SELF']);
require_once(JPAG_FUNCTIONS.'general.php');
require_once(JPAG_FUNCTIONS."core.php");



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
}


?>