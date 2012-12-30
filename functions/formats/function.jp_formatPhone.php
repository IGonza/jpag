<?php

function jp_formatPhone($phone = '', $convert = false, $trim = true){
//echo format_phone($phone, true); // Returns 1(800) 356-9377
//format_phone($phone); //Returns 1(800) FLO-WERS
	// If we have not entered a phone number just return empty
	if (empty($phone)){return '';}
	// Strip out any extra characters that we do not need only keep letters and numbers
	$phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);
	// Do we want to convert phone numbers with letters to their number equivalent?
	// Samples are: 1-800-TERMINIX, 1-800-FLOWERS, 1-800-Petmeds
	if($convert == true){
		$replace = array('2'=>array('a','b','c'),'3'=>array('d','e','f'),'4'=>array('g','h','i'),'5'=>array('j','k','l'),'6'=>array('m','n','o'),'7'=>array('p','q','r','s'),'8'=>array('t','u','v'),'9'=>array('w','x','y','z'));
		// Replace each letter with a number
		// Notice this is case insensitive with the str_ireplace instead of str_replace 
		foreach($replace as $digit=>$letters) {
			$phone = str_ireplace($letters, $digit, $phone);
		}
	}
	// If we have a number longer than 11 digits cut the string down to only 11 (16 with ext!!!)
	// This is also only ran if we want to limit only to 11 characters
	if($trim == true && strlen($phone)>16){$phone = substr($phone, 0, 16);}						 
	// Perform phone number formatting here
	$strphone = strlen($phone);
	
	if ($strphone == 7) {
		return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/","$1-$2",$phone);
	} elseif ($strphone == 10) {
		return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/","($1)$2-$3",$phone);
	} elseif ($strphone == 11) {
		return preg_replace("/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/","$1($2)$3-$4",$phone);
	}
	elseif ($strphone > 11 && $strphone < 17) {
		return preg_replace("/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})([0-9a-zA-Z])/","$1($2)$3-$4 x $5",$phone);
	}
	
	return $phone;// Return original phone if not 7, 10 or 11 digits long
}

?>