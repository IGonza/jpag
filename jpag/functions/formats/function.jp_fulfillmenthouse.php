<?php

function jp_fulfillmenthouse($var){
global $CDB;	
	if($var==0){ return '---------'; }
	
	// $type is Full State name, or just the Code
	$getfh = dbmain("SELECT cfh.`fhalias`, p.providername FROM $CDB.`4tbl_settings_fulfillment` cfh, `core_db`.`tbl_fulfillment_providers` p WHERE p.providerid = cfh.providerid AND cfh.`fhid` = $var LIMIT 1");
	if(!mysql_num_rows($getfh)){ return '---missing FH record---'; }
	$s = mysql_fetch_assoc($getfh);
	return $s['providername'].' ('.$s['fhalias'].')';
	
}

?>