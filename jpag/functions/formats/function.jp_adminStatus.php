<?php

function jp_adminStatus ($val)
{
	switch ($val) {
		case "0": 
			return "Deactive";
			break;
		case "1": 
			return "Active";
			break;
		default : 
			return $val;
	}
}

?>