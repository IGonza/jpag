<?php
//{*ordertranstype*}|{*customerid*}/{*orderid*}|View|main16x16 icon534
function jp_encryptSys4Orders($val){
	// example: 555806/{*customer_id*}|popup|main16x16 icon527 
	$separated = explode("|",$val);
	
	
	if(empty($separated[0])){ return '-'; }else{ $type = $separated[0]; }
	
	if($type==7){ $pageid = 556340; }else{ $pageid = 556337; }
	
	if(empty($separated[1])){ $hashvars = '--'; }else{ $hashvars = $separated[1]; }
	if(empty($separated[2])){ $text = ''; }else{ $text = $separated[2]; }
	if(empty($separated[3])){ $icon = ''; }else{ $icon = '<div class="'.$separated[3].'" style="float:left;display:inline;margin-right:3px;"></div>'; }
	return '<a href="#'.encrypt($pageid.'/'.$hashvars).'" class="href">'.$icon.$text.'</a>';
}

?>