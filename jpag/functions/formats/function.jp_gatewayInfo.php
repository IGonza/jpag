<?php

function jp_gatewayInfo($val){
//global $CDB;	
	if(empty($val)){  return "------------"; }
	
	$gwsql = dbmain("SELECT accountname FROM `tbl_gateways_accounts` WHERE accountid = $val LIMIT 1");
	if(!mysql_num_rows($gwsql)){ return '--no account--'; }
	$gwa = mysql_fetch_array($gwsql);
		
	return $gwa['accountname'];	
		
}

?>