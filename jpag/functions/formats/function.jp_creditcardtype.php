<?php
//this function just shows the creditcard type by passing v,m,a,d
function jp_creditcardtype($var){
		$var = strtolower($var);
		switch ($var) {
			case 'v' : $icon = 'visa.gif'; break;
			case 'vi' : $icon = 'visa.gif'; break;
			case 'visa' : $icon = 'visa.gif'; break;
			case 'm' : $icon = 'mastercard.gif'; break;
			case 'mc' : $icon = 'mastercard.gif'; break;
			case 'mastercard' : $icon = 'mastercard.gif'; break;
			case 'a' : $icon = 'amex.gif'; break;
			case 'am' : $icon = 'amex.gif'; break;
			case 'amex' : $icon = 'amex.gif'; break;
			case 'd' : $icon = 'discover.gif'; break;
			case 'di' : $icon = 'discover.gif'; break;
			case 'discover' : $icon = 'discover.gif'; break;
			default : return '<div class="divlink1L main16x16 icon1161"></div>'; break;
		}
	return '<img src="/images/icons/payment_types/'.$icon.'" width="22" />';
}
?>