<?php

/*
 * This file is a demo page with jpag
 * 
 */
require_once('dbinfo.php');
require_once('jpag/init.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

/////////////////////
// Jpag configuration here //

$jpag = new Jpag();
$jpag->set_debug(TRUE);
$jpag->set_configFile('customers_list_xml.php');

$jpag->set_dbServer(DBHOST);
$jpag->set_dbUsername(DBUSER);
$jpag->set_dbPassword(DBPASSWORD);
$jpag->set_dbName(DBNAME);

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