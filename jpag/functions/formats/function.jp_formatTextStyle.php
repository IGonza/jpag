<?php

function jp_formatTextStyle($data){
	//return easy_date_time($date,0);
	
	$separated = explode("|",$data);
	//if($separated[0]==''){ return "missing param 1"; }
	//if($separated[1]==''){ return "missing param 2"; }
	///////////////////////////////////////////////////////
	$text = $separated[0];
	$styles = (!empty($separated[1])?'color:'.$separated[1].';':'color:#000;');
	$styles .= (!empty($separated[2])?'font-weight:bold;':'');
	
	
	
	return '<span style="'.$styles.'">'.$text.'</span>';
	
}

?>