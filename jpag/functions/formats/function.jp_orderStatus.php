<?php
// this function will give a simple status of any order.  
// this function is used on all order related pages where an orderid is reviewed.
// $icons can be with icons or without

function jp_orderStatus ($val){
	$separated = explode("|",$val);
	if(!empty($separated[0])){ $CDB = $separated[0]; }else{ return "missing CDB"; }
	if(!empty($separated[1])){ $orderid = $separated[1]; }else{ return "missing orderid"; }
	if(!empty($separated[2])){ $ordertranstype = $separated[2]; }else{ return "-1-"; }
	if(!empty($separated[3])){ $orderstatus = $separated[3]; }else{ $orderstatus = '0'; }
	if(!empty($separated[4])){ $orderqueueddatetime = $separated[4]; }else{ $orderqueueddatetime = ''; }
	if(!empty($separated[5])){ $orderflag = $separated[5]; }else{ $orderflag = '0'; }
	
	return display_order_status($CDB,$orderid,$ordertranstype,$orderstatus,$orderqueueddatetime,$orderflag);

}

?>