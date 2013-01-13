<?php

/*
 * This file is a demo page with jpag
 * 
 */
require_once("jpag/init.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

/////////////////////
// Jpag configuration here //

$jpag = new Jpag();
$jpag->set_debug(TRUE);
$jpag->set_configFile('customers_list_xml.php');

$jpag->set_dbServer('jpaginatedbdemos.db.6850378.hostedresource.com');
$jpag->set_dbUsername('jpaginatedbdemos');
$jpag->set_dbPassword('PAella3130!@');
$jpag->set_dbName('jpaginatedbdemos');

if (!$jpag->load())
    echo $jpag->get_errorMsg();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Jpaginate 1.0a</title>
<?php $jpag->header();?>
    </head>
    <body>
<?php $jpag->data();?> 
    </body>
</html>