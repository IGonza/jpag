<?php

function jp_href($val){
	
	$separated = explode("|",$val);
	if(!valid_url($separated[0])){ return ''; }
	
	$url = $separated[0];
	$urltexttype = $separated[1]; // img , text, class
	$urltext = $separated[2];
	
	if($urltexttype=='img'){
		$text = '<img src="'.$urltext.'" />';
	}elseif($urltexttype=='text'){
		$text = $urltext;
	}else{
		$text = '---';
	}
	
	return '<a href="'.$url.'" target="_blank">'.$text.'</a>';

}
?>