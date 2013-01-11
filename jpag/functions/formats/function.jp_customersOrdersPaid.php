<?php
// this function will give a simple status of any order.  
// this function is used on all order related pages where an orderid is reviewed.
// $icons can be with icons or without

function jp_customersOrdersPaid ($val){
	$separated = explode("|",$val);
	if(!empty($separated[0])){ $CDB = $separated[0]; }else{ return "missing CDB"; }
	if(!empty($separated[1])){ $customerid = $separated[1]; }else{ return "missing customerid"; }
	if(!empty($separated[2])){ $paidstatus = $separated[2]; }else{ return "missing total/paid/unpaid"; }
	if(!empty($separated[3])){ $orderprocesstype = $separated[3]; } // 1 = GW, 2 = Invoice, 3 = CD
	if(!empty($separated[4])){ $ordertype = $separated[4]; }else{ $ordertype = 0; } // 0 = both, 1 = products, 2 = programs
	if(!empty($separated[5])){ $prodprogids = $separated[5]; } // also could be productid's
	if(!empty($separated[6])){ $eventids = $separated[6]; }
	if(!empty($separated[7])){ $minamount = $separated[7]; }
	if(!empty($separated[8])){ $maxamount = $separated[8]; }
	
	
	// loop thru all orders for a customer
	$amount=0.00;	
		
		if($paidstatus=='total'){
			
			if($ordertype != 1){	
				$q = "SELECT SUM(op.`paymentamount`) AS `amount` ";
				$q .= "FROM $CDB.`4tbl_orders` o ";
					$q .= "LEFT JOIN $CDB.`4tbl_orders_payments` op ON o.orderid = op.orderid ";
					$q .= "LEFT JOIN $CDB.`4tbl_orders_subscriptions` os ON o.orderid = os.orderid ";
				$q .= "WHERE o.customerid = $customerid ";
				$q .= "AND o.ordervisible = 1 "; 
				$q .= "AND o.ordertranstype IN(1,8,9) "; //1=sale,8=invoice,9=checkdraft
				$q .= "AND o.orderstatus IN(1,3,5,6,8,11) "; // 1=success,3,pending future sale,5=sub limit reached, 6=payoff reached, 8=invoice paid, 11=CD deposited
				if(!empty($orderprocesstype)) $q .= "AND o.orderprocesstype = $orderprocesstype "; // 1=cc,2=invoice,3=CD
				if(!empty($ordertype)) $q .= "AND o.ordertype = $ordertype ";
				if(!empty($prodprogids)) $q .= "AND os.programid IN($prodprogids) ";
				if(!empty($eventids)) $q .= "AND os.eventid IN($eventids) ";
				if(!empty($minamount)) $q .= "AND op.paymentamount >= $minamount ";
				if(!empty($maxamount)) $q .= "AND op.paymentamount <= $maxamount ";
			
			}else{	
				
				$q = "SELECT o.customerid, o.orderid, oi.itemtotal ";
				$q .= "FROM $CDB.`4tbl_orders` o ";
					$q .= "LEFT JOIN $CDB.`4tbl_orders_items` oi ON o.orderid = oi.orderid ";
					$q .= "LEFT JOIN $CDB.`4tbl_orders_payments` op ON o.orderid = op.orderid ";
					$q .= "LEFT JOIN $CDB.`4tbl_customers` c ON o.customerid = c.customerid ";
				$q .= "WHERE o.customerid = $customerid ";
				$q .= "AND o.ordervisible = 1 "; 
				$q .= "AND o.ordertranstype IN(1,8,9) "; //1=sale,8=invoice,9=checkdraft
				$q .= "AND o.orderstatus IN(1,3,5,6,8,11) "; // 1=success,3,pending future sale,5=sub limit reached, 6=payoff reached, 8=invoice paid, 11=CD deposited
				$q .= "AND oi.itemtype_recid IN($prodprogids) AND oi.itemtype = 1 AND o.ordertype = 1 ";
				if(!empty($orderprocesstype)) $q .= "AND o.orderprocesstype = $orderprocesstype "; // 1=cc,2=invoice,3=CD
				if(!empty($minamount)) $q .= "AND op.paymentamount >= $minamount ";
				if(!empty($maxamount)) $q .= "AND op.paymentamount <= $maxamount ";
				$q .= "ORDER BY o.`orderid` ASC ";
			}
				
		}	
		
		elseif($paidstatus=='unpaid'){
		
		
			if($ordertype != 1){	
				$q = "SELECT SUM(op.`paymentamount`) AS `amount` ";
				$q .= "FROM $CDB.`4tbl_orders` o ";
				$q .= "LEFT JOIN $CDB.`4tbl_orders_payments` op ON o.orderid = op.orderid ";
				$q .= "LEFT JOIN $CDB.`4tbl_orders_subscriptions` os ON o.orderid = os.orderid ";
				$q .= "WHERE o.customerid = $customerid ";
				$q .= "AND o.ordervisible = 1 "; 
				$q .= "AND o.ordertranstype IN(1,8,9) "; //1=sale,8=invoice,9=checkdraft
				$q .= "AND o.orderstatus IN(3) "; // 1=success,3,pending future sale,5=sub limit reached, 6=payoff reached, 8=invoice paid, 11=CD deposited
				if(!empty($orderprocesstype)) $q .= "AND o.orderprocesstype = $orderprocesstype "; // 1=cc,2=invoice,3=CD
				if(!empty($ordertype)) $q .= "AND o.ordertype = $ordertype ";
				if(!empty($prodprogids)) $q .= "AND os.programid IN($prodprogids) ";
				if(!empty($eventids)) $q .= "AND os.eventid IN($eventids) ";
				if(!empty($minamount)) $q .= "AND op.paymentamount >= $minamount ";
				if(!empty($maxamount)) $q .= "AND op.paymentamount <= $maxamount ";
				
			}else{	
				
				$q = "SELECT o.customerid, o.orderid, oi.itemtotal ";
				$q .= "FROM $CDB.`4tbl_orders` o ";
					$q .= "LEFT JOIN $CDB.`4tbl_orders_items` oi ON o.orderid = oi.orderid ";
					$q .= "LEFT JOIN $CDB.`4tbl_orders_payments` op ON o.orderid = op.orderid ";
					$q .= "LEFT JOIN $CDB.`4tbl_customers` c ON o.customerid = c.customerid ";
				$q .= "WHERE o.customerid = $customerid ";
				$q .= "AND o.ordervisible = 1 "; 
				$q .= "AND o.ordertranstype IN(1,8,9) "; //1=sale,8=invoice,9=checkdraft
				$q .= "AND o.orderstatus IN(3) "; // 1=success,3,pending future sale,5=sub limit reached, 6=payoff reached, 8=invoice paid, 11=CD deposited
				$q .= "AND oi.itemtype_recid IN($prodprogids) AND oi.itemtype = 1 AND o.ordertype = 1 ";
				if(!empty($orderprocesstype)) $q .= "AND o.orderprocesstype = $orderprocesstype "; // 1=cc,2=invoice,3=CD
				if(!empty($minamount)) $q .= "AND op.paymentamount >= $minamount ";
				if(!empty($maxamount)) $q .= "AND op.paymentamount <= $maxamount ";
				$q .= "ORDER BY o.`orderid` ASC ";
			}
				
		}	
		
		elseif($paidstatus=='paid'){
		
		
			if($ordertype != 1){	
				$q = "SELECT SUM(op.`paymentamount`) AS `amount` ";
				$q .= "FROM $CDB.`4tbl_orders` o ";
				$q .= "LEFT JOIN $CDB.`4tbl_orders_payments` op ON o.orderid = op.orderid ";
				$q .= "LEFT JOIN $CDB.`4tbl_orders_subscriptions` os ON o.orderid = os.orderid ";
				$q .= "WHERE o.customerid = $customerid ";
				$q .= "AND o.ordervisible = 1 "; 
				$q .= "AND o.ordertranstype IN(1,8,9) "; //1=sale,8=invoice,9=checkdraft
				$q .= "AND o.orderstatus IN(1,5,6,8,11,24) "; // 1=success,3,pending future sale,5=sub limit reached, 6=payoff reached, 8=invoice paid, 11=CD deposited
				if(!empty($orderprocesstype)) $q .= "AND o.orderprocesstype = $orderprocesstype "; // 1=cc,2=invoice,3=CD
				if(!empty($ordertype)) $q .= "AND o.ordertype = $ordertype ";
				if(!empty($prodprogids)) $q .= "AND os.programid IN($prodprogids) ";
				if(!empty($eventids)) $q .= "AND os.eventid IN($eventids) ";
				if(!empty($minamount)) $q .= "AND op.paymentamount >= $minamount ";
				if(!empty($maxamount)) $q .= "AND op.paymentamount <= $maxamount ";
				
			}else{	
				
				$q = "SELECT o.customerid, o.orderid, oi.itemtotal ";
				$q .= "FROM $CDB.`4tbl_orders` o ";
					$q .= "LEFT JOIN $CDB.`4tbl_orders_items` oi ON o.orderid = oi.orderid ";
					$q .= "LEFT JOIN $CDB.`4tbl_orders_payments` op ON o.orderid = op.orderid ";
					$q .= "LEFT JOIN $CDB.`4tbl_customers` c ON o.customerid = c.customerid ";
				$q .= "WHERE o.customerid = $customerid ";
				$q .= "AND o.ordervisible = 1 "; 
				$q .= "AND o.ordertranstype IN(1,8,9) "; //1=sale,8=invoice,9=checkdraft
				$q .= "AND o.orderstatus IN(1,5,6,8,11,24) "; // 1=success,3,pending future sale,5=sub limit reached, 6=payoff reached, 8=invoice paid, 11=CD deposited
				$q .= "AND oi.itemtype_recid IN($prodprogids) AND oi.itemtype = 1 AND o.ordertype = 1 ";
				if(!empty($orderprocesstype)) $q .= "AND o.orderprocesstype = $orderprocesstype "; // 1=cc,2=invoice,3=CD
				if(!empty($minamount)) $q .= "AND op.paymentamount >= $minamount ";
				if(!empty($maxamount)) $q .= "AND op.paymentamount <= $maxamount ";
				$q .= "ORDER BY o.`orderid` ASC ";
			}
				
		}	


/*			if($orderstatus==0){$text='Invoice - Incomplete';}
			if($orderstatus==3){$text='Invoice - Pending Future Action';}
			if($orderstatus==7){$text='Invoice - Awaiting Payment';}
			if($orderstatus==8){$text='Invoice - Paid In Full';}
			if($orderstatus==19){$text='Invoice - Partial Payment';}
			if($orderstatus==20){$text='Invoice - Full Refund';}
			if($orderstatus==21){$text='Invoice - Partial Refund';}
			if($orderstatus==22){$text='Invoice - Declined CC';}
			if($orderstatus==23){$text='Invoice - Bounced Check';}
			if($orderstatus==24){$text='Invoice - Approved CC';}
*/

//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
	$sql = dbmain($q);
	if(!mysql_num_rows($sql)){ return $amount; }
	
	if($ordertype != 1){	
		
		$r = mysql_fetch_assoc($sql);
		$amount = $r['amount'];
	
	}elseif($ordertype == 1){	
		
		$itemtotal=0.00;
		while ($row = mysql_fetch_array($sql))
		{
			$amount += $row['itemtotal']; // add it up
		}
		
	}	
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//////////////////////////////////////////////////////////////////////////////////////////////////////		
	return number_format($amount,2);
	
}

?>