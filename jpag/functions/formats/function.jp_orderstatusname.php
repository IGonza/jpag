<?php
// this function will give a simple status of any order.  
// this function is used on all order related pages where an orderid is reviewed.
// $icons can be with icons or without

function jp_orderstatusname ($val){
	$separated = explode("|",$val);
	if(!empty($separated[0])){ $orderstatusid = $separated[0]; }else{ return "missing 1"; }
	if(!empty($separated[1])){ $orderqueueddatetime = $separated[1]; }else{ $orderqueueddatetime = ''; }
	
	return orderstatusname_pretty($orderstatusid,$orderqueueddatetime);

}

?>