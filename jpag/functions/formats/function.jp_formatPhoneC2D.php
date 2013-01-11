<?php
// calls system 4 format_telephone() function
function jp_formatPhoneC2D($phonevar_customerid){
	$separated = explode("|",$phonevar_customerid);
	
	if(empty($separated[0])){ return ''; }else{ $customerid = $separated[0]; }
	if(empty($separated[1])){ $countrycode = ''; }else{ $countrycode = $separated[1]; }// If we have not entered a phone number just return empty
	if(empty($separated[2])){ return ''; }else{ $areacode = $separated[2]; }
	if(empty($separated[3])){ return ''; }else{ $phone = $separated[3]; }
	if(empty($separated[4])){ $ext = ''; }else{ $ext = $separated[4]; }
	
	
	return format_telephone($customerid,$countrycode,$areacode,$phone,$ext);

}

?>