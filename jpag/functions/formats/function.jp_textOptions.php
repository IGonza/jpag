<?php // this function works best for smaller status options where a database is not needed (example:  1:Active, 2:Inactive)

		//<column title="Type">
			//<content applyFunction="jp_textOptions">{*navgrouptype*}|1:System Group:main16x16 icon2083,2:Custom Group:main16x16 icon2083</content>
		//</column>

function jp_textOptions ($data)
{
	
	$separated = explode("|",$data);
	if($separated[0]==''){ return "missing param 1"; }
	if($separated[1]==''){ return "missing param 2"; }
	
	$value = $separated[0];
	$optionsarray = $separated[1];
	$options = explode(",",$optionsarray);
	
	$selected = "";
	
			foreach ($options as $val)
			{
				//list($v,$name,$icons) = explode(":", $val);
				$exploded = explode(":", $val);
				$v = $exploded[0];
				$text = $exploded[1];
				
				if(!empty($exploded[2])){ $icon = '<div class="divlink1L '.$exploded[2].'"></div>'; }else{ $icon = ''; }
				
				if ($v == $value) $selected = $icon.$text;
			}
	
	return $selected;
}
?>