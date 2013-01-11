<?php
// this function will give a simple status of any order.  
// this function is used on all order related pages where an orderid is reviewed.
// $icons can be with icons or without

function jp_invoiceLastEmailed ($val){
	$separated = explode("|",$val);
	if(!empty($separated[0])){ $CDB = $separated[0]; }else{ return "missing CDB"; }
	if(!empty($separated[1])){ $massinvoiceid = $separated[1]; }else{ return "missing massinvoiceid"; }
	if(!empty($separated[2])){ $orderid = $separated[2]; }else{ return "missing orderid"; }
	if(!empty($separated[3])){ $customerid = $separated[3]; }else{ return "missing customerid"; }
	
		$q = "SELECT timesent FROM $CDB.`4tbl_mass_invoices_emails_sent` WHERE massinvoiceid = $massinvoiceid AND orderid = $orderid AND customerid = $customerid ORDER BY ID DESC LIMIT 1";
		$sql = dbmain($q);
		if(!mysql_num_rows($sql)){ return '<span class="red">Never</span>'; }
		$r = mysql_fetch_assoc($sql);
		$timesent = easy_date_time($r['timesent'],0);

	return $timesent;
}

?>