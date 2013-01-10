<?php

function jp_genderImage ($val)
{
	switch ($val) {
		case "m": 
			return '<image src="'.JPAG_IMAGES.'icons/gender_male.png" border="0">';
			break;
		case "f":
			return '<image src="'.JPAG_IMAGES.'icons/gender_female.png" border="0">';
			break;
		default : 
			return $val;
	}
}

?>