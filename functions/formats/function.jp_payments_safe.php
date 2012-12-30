<?php // this only shows the last 4 of a cc number, or checking account

// $var1 must be pptype
// $var2 must be either bankname/cctype
// $var3 must be ccnumber/checkaccount

function jp_payments_safe ($var){
	$separated = explode("|",$var);
	
	if(empty($separated[0])){ return "missing var1"; }
	if(empty($separated[1])){ return "missing var2"; }
	if(empty($separated[2])){ return "missing var3"; }
	
	
	if($separated[0]==1){
	
	
		if(strlen($separated[1])==16) $safe = '-xxxx-xxxx-';
		if(strlen($separated[1])==15) $safe = '-xxxxxx-x';
	  
		$first4 = substr($separated[1],0,4); 
		$last4 = substr($separated[1],-4); 
	
		if($separated[1]==1){ $cctype = 'Visa '; //$cctype = 'VI';
		}elseif($separated[1]==2){ $cctype = 'Mastercard '; //$cctype = 'MC'; 
		}elseif($separated[1]==3){ $cctype = 'Discover '; //$cctype = 'DI';
		}elseif($separated[1]==4){ $cctype = 'American Express '; } //$cctype = 'AM';
	
	
		return '<div class="divlink1L main16x16 icon1161"></div><div class="divlink1L>'.$first4.$safe.$last4.'</div>';
		
	elseif($separated[0]==2){
		return '<div class="divlink1L main16x16 icon3108"></div><div class="divlink1L>xxxxxxx'.$last4.'</div>';
	}	
}

?>