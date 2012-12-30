<?php // this only shows the last 4 of a cc number, or checking account

// $var1 must be pptype
// $var2 must be either bankname/cctype
// $var3 must be ccnumber/checkaccount

function jp_creditcard_safe ($var){
	$first4 = substr($var,0,4); 
  	$last4 = substr($var,-4); 
	if(strlen($var)==16){ $safe = '-xxxx-xxxx-';}
	elseif(strlen($var)==15){ $safe = '-xxxxxx-x';}
  	else{ $safe = 'xxxxxx-'; $first4='';}
	return $first4.$safe.$last4;
}
?>