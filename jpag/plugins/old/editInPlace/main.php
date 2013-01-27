<?php

function editInPlace_content($data, $plugin_conf)
{
	//var_dump($data); die();
	$reference = isset($data['reference']) ? $data['reference'] : "";
	$count = count($plugin_conf->eid);
	
	for ($i=0;$i<$count;$i++)
	{
		if (strval($plugin_conf->eid[$i]['reference'])==$reference && strval($plugin_conf->eid[$i]['type']) == 'select' && isset($plugin_conf->eid[$i]['data']))
		{
				$values = explode("|",$plugin_conf->eid[$i]['data']);
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
	 
	return '<span class="'.$reference.'">'.$data.'</span>';
}


function editInPlace_addjs_after_get_results($plugin_conf)
{
	global $jp_dbdata_conn;
	
	$res = "";
	
	
	$num = isset($plugin_conf->eid) ? count($plugin_conf->eid) : 0;
	for ($i = 0; $i<$num; $i++)
	{
		$field_type = isset($plugin_conf->eid[$i]['type']) ? $plugin_conf->eid[$i]['type'] : "text"; // text,select
		$select_options = isset($plugin_conf->eid[$i]['data']) ? $plugin_conf->eid[$i]['data'] : "";
		$show_buttons = (isset($plugin_conf->eid[$i]['show_buttons']) && $plugin_conf->eid[$i]['show_buttons'] == "NO") ? "false" : "true";
		$show_buttons_below = (isset($plugin_conf->eid[$i]['show_buttons_below']) && $plugin_conf->eid[$i]['show_buttons_below'] == "NO") ? "false" : "true";
		
		$bg_color = (isset($plugin_conf->eid[$i]['bg_color'])) ? addslashes($plugin_conf->eid[$i]['bg_color']) : "#ffc";
		$bg_mouseover = (isset($plugin_conf->eid[$i]['bg_mouseover'])) ? addslashes($plugin_conf->eid[$i]['bg_mouseover']) : "#d7fef9";
		$bg_mouseout = (isset($plugin_conf->eid[$i]['bg_mouseout'])) ? addslashes($plugin_conf->eid[$i]['bg_mouseout']) : "#ffc";
		
		$textarea_cols = (isset($plugin_conf->eid[$i]['textarea_cols'])) ? intval($plugin_conf->eid[$i]['textarea_cols']) : "";
		$textarea_rows = (isset($plugin_conf->eid[$i]['textarea_rows'])) ? intval($plugin_conf->eid[$i]['textarea_rows']) : "";
		$textfield_width = (isset($plugin_conf->eid[$i]['textfield_width'])) ? addslashes($plugin_conf->eid[$i]['textfield_width']) : "";
		
		$saving_text = (isset($plugin_conf->eid[$i]['saving_text'])) ? addslashes($plugin_conf->eid[$i]['saving_text']) : "";
		$default_text = (isset($plugin_conf->eid[$i]['default_text'])) ? ', default_text : "'.addslashes($plugin_conf->eid[$i]['default_text']).'" ' : "";
		
		// sql option for select type
		if (isset($plugin_conf->eid[$i]['sqldata'])) {
			$select_options = "";
			$r = dbmain($plugin_conf->eid[$i]['sqldata']) or die(mysql_error());
			while ($row = mysql_fetch_assoc($r)) 
			{
				if ($select_options !="") $select_options .="|";
				$name = trim($row['name']);
				$val = trim($row['val']);
				$name = str_replace(array(':','|'), "--", $name);
				$select_options .= htmlentities($name).":".htmlentities($val);
			}
		}
		
		$res .= '
	
	
	$(".'.$plugin_conf->eid[$i]['reference'].'").each(function(i){
			id = $(this).closest("tr").attr("id");
			var parts = id.split("_");
			id = parts[parts.length-1];
			
			$(this).editInPlace({
				params: "formId='.$plugin_conf->eid[$i]['reference'].'" + "&jp_id=" + id + "&" + jp_gets_val + "&editinplace='.$field_type.'",
				field_type : "'.$field_type.'",
				select_options : "'.$select_options.'",
				show_buttons : '.$show_buttons.',
				show_buttons_below : '.$show_buttons_below.', 
				bg_color : "'.$bg_color.'", 
				bg_mouseover : "'.$bg_mouseover.'", 
				bg_mouseout : "'.$bg_mouseout.'",
				textarea_cols : "'.$textarea_cols.'", 
				textarea_rows : "'.$textarea_rows.'" ,
				textfield_width: "'.$textfield_width.'",
				saving_text : "'.$saving_text.'"
				'.$default_text.'
			});
			
	});
	';
	}

	return $res;
}

?>