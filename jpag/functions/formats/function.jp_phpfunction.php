<?php

function jp_phpfunction ($val)
{
	
	$separated = explode("|",$val);
	if(!empty($separated[0])){ $func_name = $separated[0]; }else{ return "missing V1"; }
	if(!empty($separated[1])){ $val = $separated[1]; }

	
	if (function_exists($func_name))
			return call_user_func($func_name, $val);
	//return ucwords($val);
}

?>