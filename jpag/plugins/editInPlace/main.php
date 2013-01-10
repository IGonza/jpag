<?php
function editInPlace_include_head($plugin_conf) 
{
	$res = (!isset($plugin_conf->loadJS) || $plugin_conf->loadJS == "true") ? "<script type=\"text/javascript\" src=\"".PLUGINS_REL."editInPlace/editinplace.unpacked-1.0.1.js\"></script>" : "";
	
	return $res;
}


function editInPlace_addjs_after_get_results($plugin_conf)
{
	global $jp_dbdata_conn;
	
	$res = "";
	
	
	$num = isset($plugin_conf->sql) ? count($plugin_conf->sql) : 0;
	for ($i = 0; $i<$num; $i++)
	{
		$type = isset($plugin_conf->sql[$i]['type']) ? $plugin_conf->sql[$i]['type'] : "text";
		$select_options = isset($plugin_conf->sql[$i]['data']) ? $plugin_conf->sql[$i]['data'] : "";
		$show_buttons = (isset($plugin_conf->sql[$i]['show_buttons']) && $plugin_conf->sql[$i]['show_buttons'] == "YES") ? "true" : "false";
		
		if (isset($plugin_conf->sql[$i]['sqldata'])) {
			$select_options = "";
			$r = mysql_query($plugin_conf->sql[$i]['sqldata'], $jp_dbdata_conn) or die(mysql_error());
			while ($row = mysql_fetch_assoc($r)) 
			{
				if ($select_options !="") $select_options .="|";
				$select_options .= htmlentities($row['name']).":".$row['val'];
			}
		}
		
		
		$res .= '
	
	
	$(".'.$plugin_conf->sql[$i]['class'].'").each(function(i){
			id = $(this).parent().parent().attr("id");
			$(this).editInPlace({
				url: "'.SERVER_FILE.'?load=pl_request&plugin=editInPlace",
				params: "class='.$plugin_conf->sql[$i]['class'].'" + "&jp_id=" + id + "&" + jp_gets_val + "&editinplace='.$type.'",
				field_type : "'.$type.'",
				type:"POST",
				select_options : "'.$select_options.'",
				show_buttons : '.$show_buttons.'
			});
			
	});
	';
	}

	
	return $res;
}


function editInPlace_content($data, $plugin_conf)
{
	//var_dump($data); die();
	$class = isset($data['class']) ? $data['class'] : "";
	$count = count($plugin_conf->sql);
	
	for ($i=0;$i<$count;$i++)
	{
		if (strval($plugin_conf->sql[$i]['class'])==$class && strval($plugin_conf->sql[$i]['type']) == 'select' && isset($plugin_conf->sql[$i]['data']))
		{
			if (isset($plugin_conf->sql[$i]['replaceid']) && $plugin_conf->sql[$i]['replaceid'])
			{
				$values = explode("|",$plugin_conf->sql[$i]['data']);
				foreach ($values as $val)
				{
					list($v,$k) = explode(":",$val);
					if ($data == $k) 
					{
						$data = $v;
						break;
					}
				}
			}
				
		}
	}
	 
	
	return '<div class="'.$class.'">'.$data.'</div>';
}


function editInPlace_updateData($plugin_conf)
{
	global $jp_dbdata_conn;
	
	$error = false;
	
	if (isset($_POST['class'])) $class = $_POST['class'];
	else $error = true;
	if (isset($_POST['update_value'])) $update_value = $_POST['update_value'];
	else $error = true;
	if (isset($_POST['jp_id'])) $jp_id = $_POST['jp_id'];
	else $error = true;
	$original_html = isset($_POST['original_html']) ? $_POST['original_html'] : "";
	
	if (!$error)
	{
		$num = count($plugin_conf->sql);
		for ($i=0; $i<$num; $i++) {
			if ($class == $plugin_conf->sql[$i]['class']) {
				$sql = str_replace("{*id*}", mysql_real_escape_string($jp_id), $plugin_conf->sql[$i]);
				$sql = str_replace("{*value*}", mysql_real_escape_string($update_value), $sql);
				//die();
				$res = mysql_query($sql, $jp_dbdata_conn);
				if ($res) {
					//if (isset($_POST['editinplace'])&&($_POST['editinplace']=="select"))
					//	return $original_html;
					//else 
						return "1";
				}
				else {
					//if (isset($_POST['editinplace'])&&($_POST['editinplace']=="select"))
					//	return "Error while saving!";
					//else 
						return "0";
				}
			}
		}
	}
	else {
		return "0";
	}
	
	//$value = isset($_POST['update_value']) ? $_POST['update_value'] : "";
	
	//return $value;
}
?>
