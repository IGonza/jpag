<?php

function jp_reseller_order_status($val){
	
	
	switch ($val) {
		case "0": 
			return 'undetermined';
			break;
		case "1": 
			return '<image src="/images/icons/info.png" border="0" width="13"> Prequote Phase'; // pending
			break;
		case "2": 
			return '<image src="/images/icons/icon_personal.png" border="0" width="13"> Quoted';
			break;
		case "3": 
			return '<image src="/images/icons/icon_renew.png" border="0" width="13"> Quote Accepted';
			break;
		case "4": 
			return '<image src="/images/icons/icon_exclamation.png" border="0" width="13"> Quote Denied';
			break;
		case "5": 
			return '<image src="/images/icons/cancel.png" border="0" width="13"> Order Canceled';
			break;
		case "6": 
			return '<image src="/images/icons/icon_monitor.png" border="0" width="13"> Order Submitted';
			break;
		case "7": 
			return '<image src="/images/icons/db_icon.png" border="0" width="13"> Order Shipped';
			break;
		case "8": 
			return '<image src="/images/icons/check.png" border="0" width="13"> Order Completed';
			break;
		default : 
			return $val;
	}
}

?>