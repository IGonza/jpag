<?
//// Trying to include the plugins that pointed in configure.xml
//// Plugin name and file name have to be the same.

$plugin_array = array();

foreach ($jpaginate_config->plugins->plugin as $plugin)
{
	if (isset($plugin->name) && file_exists(PLUGINS.$plugin->name."/main.php"))
	{
		include_once(PLUGINS.$plugin->name."/main.php");
		$plugin_array[] = strval($plugin->name);
	}
}

$plugin_num = count($plugin_array);
//////////////////////////////////////////////////////////



// hook that adds js script to parse/filter/update data received from the server
function jp_hook_addjs_after_get_results()
{
	global $plugin_num, $plugin_array, $plugin_conf;
	
	for ($i=0; $i<$plugin_num; $i++)
	{
			//trigger_error("yes no");
		if (function_exists($plugin_array[$i]."_addjs_after_get_results"))
			echo call_user_func($plugin_array[$i]."_addjs_after_get_results", $plugin_conf[$plugin_array[$i]]);
	}
}


function jp_hook_build_buttons()
{
	global $plugin_num, $plugin_array, $plugin_conf;
	
	$res = "";
	for ($i=0; $i<$plugin_num; $i++)
	{
		if (function_exists($plugin_array[$i]."_build_buttons"))
			$res .= call_user_func($plugin_array[$i]."_build_buttons", $plugin_conf[$plugin_array[$i]]);
	}
	return $res;
}


function jp_hook_include_head()
{
	global $plugin_num, $plugin_array, $plugin_conf;
	
	$res = "";
	for ($i=0; $i<$plugin_num; $i++)
	{
		if (function_exists($plugin_array[$i]."_include_head"))
			$res .= call_user_func($plugin_array[$i]."_include_head", $plugin_conf[$plugin_array[$i]]);
	}
	return $res;
}

function jp_hook_rebuildGenericStyle()
{
	global $plugin_num, $plugin_array, $plugin_conf;
	
	for ($i=0; $i<$plugin_num; $i++)
	{
		if (function_exists($plugin_array[$i]."_rebuildGenericStyle"))
			echo call_user_func($plugin_array[$i]."_rebuildGenericStyle", $plugin_conf[$plugin_array[$i]]);
	}
}

function jp_hook_addStyleEffects()
{
	global $plugin_num, $plugin_array, $plugin_conf;
	
	for ($i=0; $i<$plugin_num; $i++)
	{
		if (function_exists($plugin_array[$i]."_addStyleEffects"))
			echo call_user_func($plugin_array[$i]."_addStyleEffects", $plugin_conf[$plugin_array[$i]]);
	}
}

/*
// hook that saves updated data
function modules_update_value()
{
	global $plugin_num, $plugin_array;
	
	for ($i=0; $i<$plugin_num; $i++)
	{
		if (function_exists($plugin_array[$i]."_update_value"))
			echo call_user_func($plugin_array[$i]."_update_value");
	}
}

*/
// hook that adds additional js script needed for loaded plugins
function jp_hook_addjs_functions()
{
	global $plugin_num, $plugin_array, $plugin_conf;
	
	for ($i=0; $i<$plugin_num; $i++)
	{	
		if (function_exists($plugin_array[$i]."_addjs_functions"))
			echo call_user_func($plugin_array[$i]."_addjs_functions", $plugin_conf[$plugin_array[$i]]);
	}
}

// hook that add start load data function (not for the plugins actually yet)
function jp_hook_start_function()
{
	//global $plugin_num, $plugin_array, $plugin_conf;
	global $jpaginate_config;
	
	if (isset($jpaginate_config->loadStartPage) && (empty($jpaginate_config->loadStartPage) || strtolower($jpaginate_config->loadStartPage) == 'no'))
	{
		echo '$("#status_indicator").attr("style", "visibility:hidden");';
	}
	else
		echo 'loadPaginationTable(0);';
}



function jpaginate_getPluginContent($data)
{
	global $plugin_num, $plugin_array, $plugin_conf;	

	$plugin_name = strval($data['plugin']);
	if (function_exists($plugin_name."_content"))
		return call_user_func($plugin_name."_content", $data, $plugin_conf[$plugin_name]);
}

function jpaginate_plugin_updateData($plugin_name)
{
	global $plugin_num, $plugin_array, $plugin_conf;
	$r = "";
	
	if (function_exists($plugin_name."_updateData"))
	{
		$r = call_user_func($plugin_name."_updateData", $plugin_conf[$plugin_name]);
		if ($r && !empty($plugin_conf[$plugin_name]->callback) )
		{
			$f = explode(";", strval($plugin_conf[$plugin_name]->callback));
			foreach ($f as $fname)
			{
				if (function_exists($fname)){
					call_user_func($fname);	
				}
			}
		}
	}
	
	return $r;
	
}


?>