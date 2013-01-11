<?php
//this function just shows if it's a cc/echeck/token with an icon, by passing in $ppid
// $var = $ppid from system4
function jp_paymenttype($var){
global $CDB;
	$separated = explode("|",$var);
	if(!empty($separated[0])){ $ppid = $separated[0]; }else{ return '<div class="main16x16 icon1514"></div>'; }
	if(!empty($separated[0])){ $cdb = $separated[0]; }else{ return '<div class="main16x16 icon1514"></div>'; }
	
	// $type is Full State name, or just the Code
	$getppid = dbmain("SELECT `pptype` FROM $CDB.`4tbl_customers_pay` WHERE `ppid` = $ppid LIMIT 1");
	if(!mysql_num_rows($getppid)){ return '<div class="main16x16 icon1514"></div>'; }
	$pp = mysql_fetch_assoc($getppid);
		
   if($pp['pptype']==1){//credit cards
		$sql = dbmain("SELECT `cctype` FROM $CDB.`4tbl_customers_pay_cc` WHERE `ppid` = $ppid LIMIT 1");
		$r = mysql_fetch_array($sql);
		
		switch ($r['cctype']) {
			case 1 : $icon = 'visa.gif'; break;
			case 2 : $icon = 'mastercard.gif'; break;
			case 3 : $icon = 'amex.gif'; break;
			case 4 : $icon = 'discover.gif'; break;
			default : return '<div class="main16x16 icon1161"></div>'; break;
		}
		return '<img src="/images/icons/payment_types/'.$icon.'" width="22" />';
	}elseif($pp['pptype']==2){//eCheck
		return '<div class="main16x16 icon3108"></div>';
	}elseif($pp['pptype']==3){// Token
		return '<div class="main16x16 icon2336"></div>';
	}
	
}
?>