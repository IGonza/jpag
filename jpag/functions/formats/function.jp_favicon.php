<?php

function jp_favicon($val){
	$separated = explode("|",$val);
	if(empty($separated[0])){ $type = 'url'; }else{ $type = $separated[0]; } // options: url,docs
	if(empty($separated[1])){ return ''; }else{ $value = $separated[1]; }
	
	if($type == 'url'){	
		return '<image src="'.$value.'" border="0">';
	}elseif($type == 'docs'){
		if(!is_file($value)){ return ''; }
		return displayimage($value);
	}
		
}
?>