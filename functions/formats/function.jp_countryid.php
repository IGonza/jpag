<?php

function jp_countryid($var){
	$separated = explode("|",$var);
	if(empty($separated[0])){ return ""; }
	if(empty($separated[1])){ $separated[1]=2; }
	
	// $type is Full State name, or just the Code
	$getstate = dbmain("SELECT `countriesname`,`countriesisocode2`,`countriesflagicon` FROM `tbl_countries` WHERE `countriesid` = $separated[0] LIMIT 1");
	if(mysql_num_rows($getstate)){ // if a formid is waiting to be looked at
	$s = mysql_fetch_assoc($getstate);
		if(!empty($s['countriesflagicon'])){ $showicon = '<div class="'.$s['countriesflagicon'].'" style="float:left;display:inline;margin-right:5px;"></div>'; }else{ $showicon = ''; }
		if($separated[1]==1){ 
			$country = $showicon.ucfirst($s['countriesname']); }
		elseif($separated[1]==2){ 
			$country = $showicon; 
		}elseif($separated[1]==3){ 
			$country = $showicon.strtoupper($s['countriesisocode2']); 
		}
	}else{
		$country = "";	
	}
	return $country;
	
}

?>