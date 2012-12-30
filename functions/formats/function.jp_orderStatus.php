<?php
// this function will give a simple status of any order.  
// this function is used on all order related pages where an orderid is reviewed.
// $icons can be with icons or without
//display_order_status($ordertranstype,$orderstatus,$orderflag,$orderlock,$icons)

function jp_orderStatus ($val){
	$separated = explode("|",$val);
	if(!empty($separated[0])){ $ordertranstype = $separated[0]; }else{ return "-1-"; }
	if(!empty($separated[1])){ $orderstatus = $separated[1]; }else{ $orderstatus = '0'; }
	
	return display_order_status($ordertranstype,$orderstatus);

}

?>