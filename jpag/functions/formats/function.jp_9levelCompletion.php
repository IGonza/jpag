<?php

function jp_9levelCompletion($val){

    global $CDB;


	//$separateD = explode("|", $val);
	if(!empty($val)){ $loanmodid = $val; }else{ return "missing val"; }
	//if(!empty($separateD[1])){ $modstatusid = $separateD[1]; }else{ return "missing modstatusid"; }
	
	
	// get the modstatusid
	// get the list of checkboxes completed
	// return a percentage
	
	//$gwsql = dbmain("SELECT accountname FROM $CDB.`9tbl_loanmods_statuses` WHERE accountid = $val LIMIT 1");
	//if(!mysql_num_rows($gwsql)){ return '--no account--'; }
	//$gwa = mysql_fetch_array($gwsql);
		
	return $loanmodid;	
		
}

?>