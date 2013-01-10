<?php

function jp_sprite_icon ($var){
	
	$separated = explode("|",$var);
	if(empty($separated[0])){ return "missing param 1"; }
	if(empty($separated[1])){ return "missing param 2"; }
	$sprite = $separated[0];
	$icon = $separated[1]; 
	return '<div class="divlink1L '.$sprite.' '.$icon.'"></div>';
	
}
?>