<?php

	
function jp_regionid($var){
	$separated = explode("|",$var);
	if(empty($separated[0])){ return ""; }
	if(empty($separated[1])){ return ""; }


// $separated[1] is Full State name, or just the Code
	$getstate = dbmain("SELECT `regionsname`,`regionsisocode2` FROM `tbl_countries_regions` WHERE `regionsid` = $separated[0] LIMIT 1");
	if(mysql_num_rows($getstate)){ // if a formid is waiting to be looked at
	$s = mysql_fetch_assoc($getstate);
		if($separated[1]==1){ $state = ucfirst($s['regionsname']); }
		elseif($separated[1]==2){ $state = strtoupper($s['regionsisocode2']); }
	}else{
		$state = "";	
	}
	return $state;
}

?>