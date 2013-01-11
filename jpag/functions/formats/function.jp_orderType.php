<?php

function jp_orderType ($val){
global $CDB;	
	$separated = explode("|",$val);
	if(!empty($separated[0])){ $orderid = $separated[0]; }else{ return "-1-"; }
	if(!empty($separated[1])){ $ordertype = $separated[1]; }else{ return "-2-"; }
	
	if($ordertype==1){
		$ordertypetext='Custom Order';
	}elseif($ordertype==2){
		 $progsql = dbmain("SELECT p.programname, pe.eventname FROM $CDB.`4tbl_orders_subscriptions` os, $CDB.`4tbl_programs` p, $CDB.`4tbl_programs_events` pe WHERE os.programid = p.programid AND os.eventid = pe.eventid AND os.orderid = $orderid LIMIT 1");
		 $program = mysql_fetch_array($progsql);
		 $ordertypetext="$program[programname] ($program[eventname])";
	}
		
	return $ordertypetext;	
		
}

?>