<?php

function jp_external_href($val){
	
	$separated = explode("|",$val);
	if(empty($separated[0])){ return ''; } // http link
	//if(empty($separated[0])){ return '<div class="divlink1L main16x16 icon1514"></div> missing url'; } // http link
	if(empty($separated[1])){ $returnedtext=''; }else{ $returnedtext = $separated[1]; } // viewed text
	if(!empty($separated[2]) && $returnedtext!=''){ $returnedtext = substr($separated[0],0,$separated[2]).'....'; } // limit number
	if(empty($separated[3])){ $sprite = ''; }else{ $sprite = '<div class="divlink1L '.$separated[3].'"></div>'; }
	
	return '<a href="'.$separated[0].'" target="_blank" >'.$sprite.$returnedtext.'</a>';
} 

?>