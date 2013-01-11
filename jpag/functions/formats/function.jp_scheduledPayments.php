<?php
// this function will give a simple status of any order.  
// this function is used on all order related pages where an orderid is reviewed.
// $icons can be with icons or without

function jp_scheduledPayments ($val){
	$separated = explode("|",$val);
	if(!empty($separated[0])){ $CDB = $separated[0]; }else{ return "missing CDB"; }
	if(!empty($separated[1])){ $customerid = $separated[1]; }else{ return "missing customerid"; }
	
	//return display_scheduled_payments($CDB,$customerid);
	
	// loop thru all orders for a customer
	$i=0;
	$yay='';	
		$q = "SELECT o.orderid, o.orderdatecreated, o.ordertranstype, o.orderstatus, o.orderflag, o.orderqueueddatetime, op.paymentamount ";
		$q .= "FROM $CDB.`4tbl_orders` o, $CDB.`4tbl_orders_payments` op, $CDB.`4tbl_orders_subscriptions` os ";
		$q .= "WHERE o.orderid = op.orderid AND o.orderid = os.orderid ";
		$q .= "AND o.customerid = $customerid ";
		$q .= "ORDER BY o.orderdatecreated ASC ";
		
		$sql = dbmain($q);
		if(mysql_num_rows($sql)){
		$yay.='<table class="itemstable">';
			$yay .= "<tr>";
				$yay .= "<th></th>";
				$yay .= "<th>Date Inserted</th>";
				$yay .= "<th>Order Status</th>";
				$yay .= "<th>Amount</th>";
			$yay .= "</tr>";
		
		while($r = mysql_fetch_array($sql)){
			$i++;
			$yay .= "<tr>";
				$yay .= "<td>$i</td>";
				$yay .= "<td>".easy_date_time($r['orderdatecreated'],0)."</td>";
				$yay .= "<td>".display_order_status($CDB,$r['orderid'],$r['ordertranstype'],$r['orderstatus'],$r['orderqueueddatetime'],$r['orderflag'],3)."</td>";
				$yay .= "<td tyle='text-align:right;'>$r[paymentamount]</td>";
			$yay .= "</tr>";
			
			
		}
			$yay .= "</table>";
		}
	return $yay;
}

?>