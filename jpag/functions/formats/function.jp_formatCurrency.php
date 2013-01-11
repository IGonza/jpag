<?php
function jp_formatCurrency($var){

	$separated = explode("|",$var);
	if(!empty($separated[0])){ $amount1 = $separated[0]; }else{ $amount1 = $separated[0]; }
	if(!empty($separated[1])){ $amount2 = $separated[1]; }else{ $amount2 = 0.00; }


    if($amount2>0.00){ 
		$value = '<span class="grey_strikethru">$'.number_format($amount1,2).'</span>';//red, parenthasis, negative
		$newvalue = ' <span>$'.number_format($amount2,2).'</span> ';
	}else{
		$value = '<span>$'.number_format($amount1,2).'</span>';//red, parenthasis, negative
		$newvalue='';
	}

	return $value.$newvalue;

}

?>