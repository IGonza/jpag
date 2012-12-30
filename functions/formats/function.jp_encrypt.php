<?php

function jp_encrypt($val){
	// example: 555806/{*customer_id*}|popup|main16x16 icon527 
	$separated = explode("|",$val);
	if(empty($separated[1])){ $text = ''; }else{ $text = $separated[1]; }
	if(empty($separated[2])){ $icon = ''; }else{ $icon = '<div class="'.$separated[2].'" style="float:left;display:inline;margin-right:3px;"></div>'; }
	return '<a href="#'.encrypt($separated[0]).'" class="href">'.$icon.$text.'</a>';
}

?>