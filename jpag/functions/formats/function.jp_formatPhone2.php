<?php
// new phone formatter for domestic and international
function jp_formatPhone2($phonevar_customerid){
	$separated = explode("|",$phonevar_customerid);
	
	if(empty($separated[0])){ return ''; }else{ $customerid = $separated[0]; }
	if(empty($separated[1])){ $countrycode = ''; }else{ $countrycode = $separated[1]; }// If we have not entered a phone number just return empty
	if(empty($separated[2])){ return ''; }else{ $areacode = $separated[2]; }
	if(empty($separated[3])){ return ''; }else{ $phone = $separated[3]; }
	if(empty($separated[4])){ $ext = ''; }else{ $ext = " x".$separated[4]; }

	// Strip out any extra characters that we do not need only keep letters and numbers
	$phone = preg_replace("/[^0-9A-Za-z]/", "", $areacode.$phone);
	
	if($countrycode==1){
	// Perform US phone number formatting here
		$strphone = strlen($phone);
		if ($strphone == 10) { // auto-format US PHones
			$phone = preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/","($1) $2-$3",$phone);
		}
	
	}else{
		$countrycode = "+$countrycode-";	
	}
	
	$phone = $countrycode.$phone.$ext;
	
	
	if(!empty($_SESSION['tools']['tool_click2dial'])){
		$phone = '<img src="/images/icons/telephone_blue3.png" style="position:relative;bottom:-3px;padding-right:2px;" border="0" /><a href="#'.encrypt("558360/$customerid/$phone").'" class="hrefpop" width="650" height="550">'.$phone.'</a>';
	}
	
	
	
	return $phone;
}

?>