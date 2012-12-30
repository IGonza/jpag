<?php
function jp_formatCurrency2($var){
	$separated = explode("|",$var);
	if(empty($separated[0])){ return '-'; }else{ $amount = $separated[0]; }
	if(empty($separated[1])){ return '--'; }else{ $type = $separated[1]; }
	if(empty($separated[2])){ $status=''; }else{ $status = $separated[2]; }
	
	if(in_array($type,array(3,4,5,7))){ 
		if($status==1){ // red, parenthasis, negative
			return '<span class="red">($'.number_format($amount,2).')</span>';
		}else{ 
			return '($'.number_format($amount,2).')'; 
		}
	}
	
	return '<span class="darkgreen">$'.number_format($amount,2).'</span>';
	
	//return "$".number_format($amount,2);

}

?>