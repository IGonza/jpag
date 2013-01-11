<?php
//anything public
function jp_coreimages ($val){
	$separated = explode("|",$val);
	
	if(empty($separated[0])){ return ''; }else{ $imgsrc = $separated[0]; }
	if(empty($separated[1])){ $style = ''; }else{ $style = ' style="'.$separated[1].'"'; }

	return '<img src="'.$imgsrc.'"'.$style.'> ';
}

?>