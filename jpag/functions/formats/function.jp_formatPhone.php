<?php

function jp_formatPhone($phone){
	if(empty($phone)){ return ''; }
	$exploded = explode(' ',$phone);
	
	$countrycode = $exploded[0];
	$areacode = '('.$exploded[1].')';
	$number = $exploded[2];
	$ext = (!empty($exploded[3])?'x'.$exploded[3]:'');
	$strphone = strlen($number);
	
	
	if($countrycode==1 && $strphone == 7){ // domestic
		$prefix = substr($number,0,3);
		$suffix = substr($number,-4);
		$phone = $countrycode.' '.$areacode.' '.$prefix.'-'.$suffix.' '.$ext;
	}else{
		// international or bad US number
		$phone = '+'.$countrycode.' '.$areacode.' '.$number.' '.$ext;
	}
	
	
	return $phone;
}

?>