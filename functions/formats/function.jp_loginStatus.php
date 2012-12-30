<?php

function jp_loginStatus ($val)
{
	switch ($val) {
		case "0": 
			return '<image src="/images/icons/icon_delete.png" border="0" title="Disabled Account"> Deactivated';
			break;
		case "1": 
			return '<image src="/images/icons/check.png" border="0" title="Active Account"> Active';
			break;
		case "2":
			return '<image src="/images/icons/warning.png" border="0" title="Password Locked"> Locked';
			break;
		default : 
			return $val;
	}
}

?>