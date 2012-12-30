<?php

function rowsActions_addjs_functions($plugin_conf)
{
	global $plugin_array;

	if (in_array("checkRows", $plugin_array)) {
	$res = '
		$("#jp_rowsActionswButton").live("click", function() {
				var link = "";
				
				$(".jp_checkrows:checked").each( function(i){ link = link + $(this).parent().parent().attr("id") + ",";});
				
				alert(link);
				//$(this).attr("href", link);
				//return true;
		});
		';
	return $res;
	}
	else return "";

}



function rowsActions_build_buttons($plugin_conf)
{
	global $plugin_array;
	
	if (in_array("checkRows", $plugin_array)) {
		$title = isset($plugin_conf->buttonName)&&!empty($plugin_conf) ? htmlentities($plugin_conf->buttonName) : "Rows Actions";
		$output = '&nbsp;<input id="jp_rowsActionswButton" type="button" value="'.$title.'" />';
		return $output;
	}
	else 
		return "";
} 


/*function rowsActions_build_buttons($plugin_conf)
{
	global $plugin_array;
	
	if (in_array("checkRows", $plugin_array)) {
		$output = '<select id="jp_selectrowsActions">';
		foreach ($plugin_conf->url as $url) {
			$title = isset($url['caption']) ? $url['caption'] : $url;
			$output .= '<option value="'.$url.'">'.htmlentities($title).'</option>';
		}
		$output .= '</select>';
		$title = isset($plugin_conf->buttonName)&&!empty($plugin_conf) ? htmlentities($plugin_conf->buttonName) : "Rows Actions";
		
		$output .= '&nbsp;<a href="" id="jp_rowsActionswButton" target="_blank">'.$title.'</a> ';
		
		return $output;
	}
	else 
		return "";
} */

?>
