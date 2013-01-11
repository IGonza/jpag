<?php
function jp_ordertotal($var){
global $CDB;
	$separated = explode("|",$var);
	if(empty($separated[0])){ return '-'; }else{ $orderid = $separated[0]; }
	if(empty($separated[1])){ $orderstatusid=''; }else{ $orderstatusid = $separated[1]; }
	if(empty($separated[2])){ return '-'; }else{ $ordertotal = $separated[2]; }
	if(empty($separated[3])){ $overridetotal=''; }else{ $overridetotal = $separated[3]; }
	if(empty($separated[4])){ $ppid=''; }else{ $ppid = $separated[4]; }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// grab the orderstatustypeid here
		if(empty($orderstatusid)){
			trigger_error("missing orderstatusid <br> $CDB, $orderid");
			return "sos";
		}
		$q=dbmain("SELECT orderprocesstype,orderstatustypeid FROM $CDB.`4tbl_settings_orders_statuses` WHERE orderstatusid = $orderstatusid");
		if(!mysql_num_rows($q)){ 
			trigger_error("missing orderstatusid <br> $CDB, $orderid");
			return "sos";
		}
		$r = mysql_fetch_assoc($q);
		$orderprocesstype = $r['orderprocesstype'];  
		$orderstatustypeid = $r['orderstatustypeid'];  
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$newvalue = $value = '';
	if($orderstatustypeid == 4){ // refund, partial refund, full refund, credits
		$refundstuff=dbmain("SELECT SUM(refundamount) AS totalrefunded FROM $CDB.`4tbl_transactions_apis_refund` WHERE `orderid` = $orderid");
		$r = mysql_fetch_assoc($refundstuff);
		$refundedamount = $r['totalrefunded'];  
		
		if(!empty($refundedamount)) $newvalue = '<span class="red">($'.number_format($refundedamount,2).')</span> ';//red, parenthasis, negative
		if($overridetotal>0.00) $newvalue .= ' <span class="darkgreen">$'.number_format($overridetotal,2).'</span>';
		$value = ' <span class="grey_strikethru">$'.number_format($ordertotal,2).'</span>';
	
	}elseif($overridetotal>0.00){ 
		$newvalue = '<span class="darkgreen">$'.number_format($overridetotal,2).'</span> ';
		$value = '<span class="grey_strikethru">$'.number_format($ordertotal,2).'</span>';
	}else{
		$value = '<span class="darkgreen">$'.number_format($ordertotal,2).'</span>';
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$payicon='';
	if($orderprocesstype == 1){ // credit cards
		if(empty($ppid)){$payicon = '<div class="divlink1L main16x16 icon1517"></div>'; }else{ 
			$getppid = dbmain("SELECT pptype FROM $CDB.`4tbl_customers_pay` WHERE ppid = $ppid LIMIT 1");
			if(!mysql_num_rows($getppid)){ $payicon = '<div class="divlink1L main16x16 icon1514"></div>'; }else{
			$pp = mysql_fetch_assoc($getppid);
			
			   if($pp['pptype']==1){//credit cards
					$sql = dbmain("SELECT cctype FROM $CDB.`4tbl_customers_pay_cc` WHERE ppid = $ppid LIMIT 1");
					$r = mysql_fetch_array($sql);
					switch ($r['cctype']) {
						case 1 : $payicon = '<img width="22" class="divlink1L" src="/images/icons/payment_types/visa.gif" />'; break;
						case 2 : $payicon = '<img width="22" class="divlink1L" src="/images/icons/payment_types/mastercard.gif" />'; break;
						case 3 : $payicon = '<img width="22" class="divlink1L" src="/images/icons/payment_types/amex.gif" />'; break;
						case 4 : $payicon = '<img width="22" class="divlink1L" src="/images/icons/payment_types/discover.gif" />'; break;
						default : $payicon = '<div class="divlink1L main16x16 icon1517"></div>'; break;
					}
				}elseif($pp['pptype']==2){//bank account
					$payicon = '<div class="divlink1L main16x16 icon3108"></div>';
				}elseif($pp['pptype']==3){// Token
					$payicon = '<div class="divlink1L main16x16 icon2336"></div>';
				}else{
					$payicon = '<div class="divlink1L main16x16 icon1161"></div>';
				}
			}
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	return $payicon.$newvalue.$value;
}

?>