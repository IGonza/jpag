<?php

function colorbox_include_head($plugin_conf) 
{
	$res = (!isset($plugin_conf->loadJS) || $plugin_conf->loadJS == "true") ? "<script type=\"text/javascript\" src=\"".PLUGINS_REL."colorbox/jquery.colorbox-min.js\"></script>" : "";
	$res .=	"<link type=\"text/css\" media=\"screen\" rel=\"stylesheet\" href=\"".PLUGINS_REL."colorbox/colorbox.css\" />";
	return $res;
}


function colorbox_addjs_after_get_results($plugin_conf)
{
	$res = "";
	
	
	$num = isset($plugin_conf->items) ? count($plugin_conf->items) : 0;
	for ($i = 0; $i<$num; $i++)
	{
		$hidelink = (isset($plugin_conf->items[$i]['hideLink']) && $plugin_conf->items[$i]['hideLink']=="YES") ? 1 : 0;
		$classes = "";
		if (isset($plugin_conf->items[$i]['classes']) && !empty($plugin_conf->items[$i]['classes']))
		$classes = str_replace(";", ",.", $plugin_conf->items[$i]['classes']);

		$attr = isset($plugin_conf->items[$i]['attributes']) ? $plugin_conf->items[$i]['attributes'] : "";
		if (!empty($classes)) {
			if ($hidelink) {
				$res .= '
		colobox_elements = $(".'.$classes.'");
		colobox_elements.each(function(i, val) {
				tmp_href = $(this).attr("href");
				
				$(this).attr("href", "javascript:void(0)");
				$(this).colorbox({href:tmp_href,'.$attr.'});
		});
		';
			}
			else {
				$res .= "\n".'$(".'.$classes.'").colorbox({'.$attr.'});';
			}
		}
	}
	
	
	return $res;
}
?>