<?php

function jp_trailtext ($val){
	$separated = explode("|",$val);
	if(empty($separated[0])){ return ""; }
	if(empty($separated[1])){ return ""; }

    if( strlen($separated[1]) >= $separated[0] ){ 
	
		return strip_tags(substr($separated[1],0,$separated[0])).'...<a href="javascript:void(0);" title="'.$separated[1].'"><img src="/images/icons/info.png" border="0"></a>';
	   
	    }else{

	  return $separated[1];
	
	}
}

?>