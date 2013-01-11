<?php // this function works best for smaller status options where a database is not needed (example:  1:Active, 2:Inactive)

		//<column title="Type">
			//<content applyFunction="jp_textOptions">{*databaserecord*}|1:Condition Text:div class[main16x16 icon2083]:span class[boldgreen]:image path[/images/icons/rss.gif]</content>
			//example: <content applyFunction="jp_textOptions">{*navgrouptype*}|1:System Group:main16x16 icon2083::,2:Custom Group:::/images/icons/rss.gif</content>
		//</column>

function jp_textOptions ($data)
{
	
	$separated = explode("|",$data);
	if($separated[0]==''){ return "missing param 1"; }
	if($separated[1]==''){ return "missing param 2"; }
	///////////////////////////////////////////////////////
	///////////////////////////////////////////////////////
	$value = $separated[0];
	$value_exploded = explode(":",$value);
	$value = $value_exploded[0];
	$default_text = (!empty($value_exploded[1]))?$value_exploded[1]:'';
	$default_icon = (!empty($value_exploded[2]))?$value_exploded[2]:'';
	$default_class = (!empty($value_exploded[3]))?$value_exploded[3]:'';
	$default_img = (!empty($value_exploded[4]))?$value_exploded[4]:'';

	if(!empty($default_icon)){ $icon = '<div class="'.$default_icon.'"></div>'; }
	elseif(!empty($default_img)){ $icon = '<img src="'.$default_img.'" />'; }else{ $icon = ''; }
	$selected = $icon.$default_text;
	///////////////////////////////////////////////////////
	///////////////////////////////////////////////////////
	$optionsarray = $separated[1];
	$options = explode(",",$optionsarray);
	
		foreach ($options as $val)
		{
			//list($v,$name,$icons) = explode(":", $val);
			$exploded = explode(":", $val);
			$v = $exploded[0]; // database value
			$text = $exploded[1]; // visual text
			// icon option
			if(!empty($exploded[2])){ $icon = '<div class="'.$exploded[2].'"></div>'; }elseif(!empty($exploded[4])){ $icon = '<img src="'.$exploded[4].'" class="divlink1L" />'; }else{ $icon = ''; }
			// visual text style
			$textclass = !empty($exploded[3])?$exploded[3]:'';
		
			if ($v == $value){
				if(!empty($textclass)){ $selected = $icon.'<span class="'.$textclass.'">'.$text.'</span>'; }else{ $selected = $icon.$text; }
			}
		
		}
	
	return $selected;
}
?>