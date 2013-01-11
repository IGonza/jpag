<?php
function jp_formatCurrency2($var){
global $CDB;
	$separated = explode("|",$var);
	if(empty($separated[0])){ return '-'; }else{ $amount = $separated[0]; }
	if(empty($separated[1])){ return '--'; }else{ $ordertranstype = $separated[1]; }
	if(empty($separated[2])){ $status=''; }else{ $status = $separated[2]; }
	if(empty($separated[3])){ $override=''; }else{ $override = $separated[3]; }
	if(empty($separated[4])){ $ppid=''; }else{ $ppid = $separated[4]; }
	if(empty($separated[5])){ return '=='; }else{ $orderid = $separated[5]; }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$newvalue = $value = '';
	if(in_array($ordertranstype,array(3,4,5))){ // 3=refund,4=partial refund,5=full refund with partials,7=credit
	
		$refundstuff=dbmain("SELECT SUM(refundamount) AS totalrefunded FROM $CDB.`4tbl_transactions_apis_refund` WHERE `orderid` = $orderid");
		$r = mysql_fetch_assoc($refundstuff);
		$refundedamount = $r['totalrefunded'];  
		
		if(!empty($refundedamount)) $newvalue = '<span class="red">($'.number_format($refundedamount,2).')</span> ';//red, parenthasis, negative
		if($override>0.00) $newvalue .= ' <span class="darkgreen">$'.number_format($override,2).'</span>';
		$value = ' <span class="grey_strikethru">$'.number_format($amount,2).'</span>';
	
	}elseif($override>0.00){ 
		$newvalue = '<span class="darkgreen">$'.number_format($override,2).'</span> ';
		$value = '<span class="grey_strikethru">$'.number_format($amount,2).'</span>';
	}else{
		$value = '<span class="darkgreen">$'.number_format($amount,2).'</span>';
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$payicon='';
	if(in_array($ordertranstype,array(1,2,3,4,5,6,7))){
		if(empty($ppid)){$payicon = '<div class="divlink1L main16x16 icon1517"></div>'; }else{ 
			$getppid = dbmain("SELECT `pptype` FROM $CDB.`4tbl_customers_pay` WHERE `ppid` = $ppid LIMIT 1");
			if(!mysql_num_rows($getppid)){ $payicon = '<div class="divlink1L main16x16 icon1514"></div>'; }else{
			$pp = mysql_fetch_assoc($getppid);
			
			   if($pp['pptype']==1){//credit cards
					$sql = dbmain("SELECT `cctype` FROM $CDB.`4tbl_customers_pay_cc` WHERE `ppid` = $ppid LIMIT 1");
					$r = mysql_fetch_array($sql);
					switch ($r['cctype']) {
						case 1 : $payicon = '<img src="/images/icons/payment_types/visa.gif" width="22" class="divlink1L" />'; break;
						case 2 : $payicon = '<img src="/images/icons/payment_types/mastercard.gif" width="22" class="divlink1L" />'; break;
						case 3 : $payicon = '<img src="/images/icons/payment_types/amex.gif" width="22" class="divlink1L" />'; break;
						case 4 : $payicon = '<img src="/images/icons/payment_types/discover.gif" width="22" class="divlink1L" />'; break;
						default : $payicon = '<div class="divlink1L main16x16 icon1517"></div>'; break;
					}
				}elseif($pp['pptype']==2){//eCheck
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