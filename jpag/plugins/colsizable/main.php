<?php
function colsizable_include_head($plugin_conf) 
{
	
	$res = (!isset($plugin_conf->loadJS) || $plugin_conf->loadJS == "true") ? "<script type=\"text/javascript\" src=\"".PLUGINS_REL."colsizable/jquery.event.drag-1.4.js\"></script>" : "";
	$res .= (!isset($plugin_conf->loadJS) || $plugin_conf->loadJS == "true") ? "<script type=\"text/javascript\" src=\"".PLUGINS_REL."colsizable/jquery.colsizable-0.1.js\"></script>" : "";
	
	$res .=	"<link type=\"text/css\" rel=\"stylesheet\" href=\"".PLUGINS_REL."colsizable/jquery.kiketable.colsizable.css\" />";

	return $res;
}

function colsizable_addjs_after_get_results($plugin_conf)
{
	$res = '$(".jp_data").kiketable_colsizable({dragMove : false, 
				dragOpacity: .5,
				dragCells : "tr:first>*:not(:first)",
				dragProxy: "line"
				
});';
	
	return $res;
}
?>