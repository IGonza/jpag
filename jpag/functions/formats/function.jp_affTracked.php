<?php

function jp_affTracked($val){
	
	$separated = explode("|",$val);
	if(!empty($separated[0])){ $CDB = $separated[0]; }else{ return "missing CDB"; }
	if(!empty($separated[1])){ $orderid = $separated[1]; }else{ return "missing orderid"; }
	if(!empty($separated[2])){ $startdate = $separated[2]; }else{ return "missing startdate"; }
	if(!empty($separated[3])){ $enddate = $separated[3]; }else{ return "missing enddate"; }
	
	//trigger_error("SELECT trackingid FROM `$CDB`.`6tbl_tracking` WHERE `datetracked` BETWEEN '$startdate' AND '$enddate' AND orderid = $orderid LIMIT 1");
	$sql = dbmain("SELECT trackingid FROM `$CDB`.`6tbl_tracking` WHERE `datetracked` BETWEEN '$startdate' AND '$enddate' AND orderid = $orderid LIMIT 1");
	if(!mysql_num_rows($sql)){ return '<div class="divlink1L main16x16 icon1517"></div>'; } // icon exclaim
	
	return '<div class="divlink1L main16x16 icon2841"></div>'; // icon green check
	
}

?>