<?php

function jp_sys4Functions ($val)
{
	$separated = explode("|",$val);
	$func = $separated[0]; 
	//return $func;


	switch ($func)
	{
		case 1:
			return '---';
		case 2:
			$funcvalue1 = $separated[1]; // customerid
			$funcvalue2 = $separated[2]; // note categoryid
			return notes_last_date($funcvalue1,$funcvalue2);
		default:
			return '---';

	}	
	
	
}

?>