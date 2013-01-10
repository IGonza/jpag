<?php 

function tooltip_content($data, $plugin_conf)
{
	$class = isset($data['class']) ? trim($data['class']) : "";
	$content = explode("|", $data, 2);
	$content[1] = (isset($content[1])) ? $content[1] : "";
	return '<div class="'.$class.'" rel="'.trim($content[1]).'">'.$content[0].'</div>';
}


function tooltip_addjs_after_get_results($plugin_conf)
{
	global $jp_dbdata_conn;
	
	$res = "";
	
	
	$num = isset($plugin_conf->classname) ? count($plugin_conf->classname) : 0;
	for ($i = 0; $i<$num; $i++)
	{
		$class = isset($plugin_conf->classname[$i]) ? trim($plugin_conf->classname[$i]): ""; 
		$style = isset($plugin_conf->classname[$i]['style']) ? trim($plugin_conf->classname[$i]['style']): "light";
		$tooltip = isset($plugin_conf->classname[$i]['tooltip']) ? trim($plugin_conf->classname[$i]['tooltip']): "topLeft";
		$target = isset($plugin_conf->classname[$i]['target']) ? trim($plugin_conf->classname[$i]['target']): "topMiddle";
		$maxwidth = isset($plugin_conf->classname[$i]['maxwidth']) ? intval($plugin_conf->classname[$i]['maxwidth']): "250";
		
		if ($class)
		{
			$res .= '
	
$(".'.$class.'").each(function()
{
	var rel = $(this).attr(\'rel\');
	var cont = rel.split(\'|\');
	//var tooltip_content = "";
	
	if (cont[0]==\'url\')
	{

		$(this).qtip({
			content: {url: cont[1], method: \'post\'},
	 		position: { 
				corner: {
			 		tooltip: \''.$tooltip.'\',
			 		target: \''.$target.'\'
				}
			}, 
      		show:\'mouseover\',
			hide: {event:\'mouseout\',fixed:true},
			delay:0,
     		style: {
      	    	  padding: \'5px 5px\',
      	      	name: \''.$style.'\',
      	      	tip: \''.$tooltip.'\',
      	      	width: { max: \''.$maxwidth.'\' } 
        	}
		});
	}
	else 
	{
		//tooltip_content = cont[1];
		$(this).qtip({
			content: cont[1],
	 		position: { 
				corner: {
			 		tooltip: \''.$tooltip.'\',
			 		target: \''.$target.'\'
				}
			}, 
      		show:\'mouseover\',
			hide: {event:\'mouseout\',fixed:true},
			delay:0,
     		style: {
      	    	  padding: \'5px 5px\',
      	      	name: \''.$style.'\',
      	      	tip: \''.$tooltip.'\'
        	}
		});
	}
});
			

			';
		}
	}

	
	return $res;
}
?>