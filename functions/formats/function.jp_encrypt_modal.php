<?php


function jp_encrypt_modal($val){
	// example: 555806/{*customer_id*}|popup|main16x16 icon527|700|450
	$separated = explode("|",$val);
	if(empty($separated[1])){ $text = ''; }else{ $text = $separated[1]; }
	if(empty($separated[2])){ $icon = ''; }else{ $icon = '<div class="'.$separated[2].'" style="float:left;display:inline;margin-right:3px;"></div>'; }
	if(empty($separated[3])){ $width = "650"; }else{ $width = $separated[3]; }
	if(empty($separated[4])){ $height = "550"; }else{ $height = $separated[4]; }
	return $icon.' <a href="#'.encrypt($separated[0]).'" class="hrefmodal" width="'.$width.'" height="'.$height.'">'.$text.'</a>';
}

?>