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
$jpag->set_configType('json'); // optional. Default : json. Other types are not implemented yet.

$config = file_get_contents("notes.json");
$jpag->set_configString($config);

$connectionData = array(
    "dbms"       => "mysql",
    "dbhost"     => DBHOST,
    "dbuser"     => DBUSER,
    "dbpassword" => DBPASSWORD,
    "dbname"     => DBNAME
);

$jpag->load("db", $connectionData);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Jpaginate 1.0a</title>
<?php echo $jpag->header();?>
    </head>
    <body>
<?php echo $jpag->data();?> 
    </body>
</html>