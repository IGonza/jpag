<?php

function simpleStyleEffects_rebuildGenericStyle ($plugin_conf)
{
	$effect_name = isset($plugin_conf->effect) && !empty($plugin_conf->effect) ? trim($plugin_conf->effect) : "";
	$res = "";
	
	
	switch ($effect_name) {
		case "OddEvenRow": 
			$res = "$('.jp_data tr:odd').addClass('jp_oddRow');"."\n";
			$res .= "$('.jp_data tr:even').addClass('jp_evenRow');"."\n";
			break;
		case "rollover" : 
			$res =  "$('.jp_data tr:has(td)').bind('mouseover',function(){ $(this).children().addClass('jp_hoverMiddle'); });"."\n";
			$res .= "$('.jp_data tr:has(td)').bind('mouseout',function(){ $(this).children().removeClass('jp_hoverMiddle'); });"."\n";	
			break;
	}
	
	return $res;
}

?>
