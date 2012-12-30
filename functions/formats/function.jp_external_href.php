<?php

function jp_external_href($val){
	
	$separated = explode("|",$val);
	if(empty($separated[0])){ return "missing param 1"; } // http link
	if(empty($separated[1])){ return "missing param 2"; } // viewed text
	if(empty($separated[2])){ return "missing param 3"; } // limit number
	
	
	$returnedtext = substr($separated[0],0,$separated[2]).'....';
	
	return '<a href="'.$separated[0].'" target="_blank" ><div class="divlink1L main16x16 icon1656"></div>'.$returnedtext.'</a>';
} 

?>