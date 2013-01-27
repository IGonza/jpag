<?

function flagger_content($data, $plugin_conf)
{

	$d = "";
	$class = isset($plugin_conf->sql['class']) && !empty($plugin_conf->sql['class']) ? trim($plugin_conf->sql['class']) : "randomclassname";
	/*
	$num = count($plugin_conf->sql);
	for ($i=0; $i<$num; $i++) {
		if ($class == strval($plugin_conf->sql[$i]['class'])) {
			
			if(intval($data) == 1){
				$c = (isset($plugin_conf->sql[$i]['img1'])) ? addslashes($plugin_conf->sql[$i]['img1']) : "/images/icons/star_icon.gif";
				$d = '<image src="'.$c.'" border="0">';
				$c = (isset($plugin_conf->sql[$i]['sprite1'])) ? addslashes($plugin_conf->sql[$i]['sprite1']) : "";
				if ($c) $d = '<div class="'.$c.'" />';
				$v = 'on';
			}
			else{ 
				$c = (isset($plugin_conf->sql[$i]['img0'])) ? addslashes($plugin_conf->sql[$i]['img0']) : "/images/icons/star_icon_faded.gif";
				$d = '<image src="'.$c.'" border="0">';
				$c = (isset($plugin_conf->sql[$i]['sprite0'])) ? addslashes($plugin_conf->sql[$i]['sprite0']) : "";
				if ($c) $d = '<div class="'.$c.'" />'; 
				$v = 'off';
			}
			
		}
	}*/
	$class = 'flagger';
	if(intval($data) == 1){
		$d = '<img src="/images/icons/star_icon.gif" border="0">';
		$v = 'on';
	}else{ 
		$d =  '<img src="/images/icons/star_icon_faded.gif" border="0">'; 
		$v = 'off';
	}
	
	return '<div class="'.$class.' jp_fl_'.$v.'">'.$d.'</div>';

}


function flagger_addjs_after_get_results($plugin_conf)
{
	$res = "";
	
	
	$num = isset($plugin_conf->sql) ? count($plugin_conf->sql) : 0;
	for ($i = 0; $i<$num; $i++)
	{
	
	$res .=
	'
	$(".'.$plugin_conf->sql[$i]['class'].'").each(function(){
			$(this).click(function(){
				
				id = $(this).closest("tr").attr("id");
				var parts = id.split("_");
				id = parts[parts.length-1]; // get last of array
				
				//alert("flag clicked with row_id=" + id);
				var v; 
				if ($(this).hasClass("jp_fl_off"))
					v = 0;
				else v=1;
				
				vars = "jp_id="+id+"&plugin=flagger&v=" + v + "&class='.$plugin_conf->sql[$i]['class'].'";
				jp_sendRequest(vars, "jp_flagger_response");
			
			});
			
	});
	';
	}
	
	return $res;	
}

function flagger_addjs_functions($plugin_conf)
{
	
	
	$res = '
	
	function jp_flagger_response(data)
	{
		if (data != "0") {
			//alert("data: " +data); // data: 289755:flagger:<image src="/images/icons/star_icon.gif" border="0">
			var jp_flagger_d = data.split(":");

			var jp_flagger_el = $("[id$=\'"+ jp_flagger_d[0] +"\']").children("td").find("div."+ jp_flagger_d[1]);
			
			jp_flagger_el.html(jp_flagger_d[2]);
			
			if (jp_flagger_el.hasClass("jp_fl_off"))
				jp_flagger_el.removeClass("jp_fl_off").addClass("jp_fl_on");
			else
				jp_flagger_el.removeClass("jp_fl_on").addClass("jp_fl_off");
			
			
			
			$("#status_indicator").attr("style", "visibility:hidden");
		}
		else 
			alert("something went wrong! " +data);
			$("#status_indicator").attr("style", "visibility:hidden");
	}
	
	';
	return $res;
}


function flagger_updateData($plugin_conf)
{
	global $jp_dbdata_conn;
	
	$id = isset($_GET['jp_id']) ? trim($_GET['jp_id']) : "";
	$value = isset($_GET['v']) ? intval($_GET['v']) : "";
	
	if (isset($_GET['class'])) $class = $_GET['class'];
	else $class = "";
	
	//return '289955:flagger:<image src="/images/icons/star_icon.gif" border="0">';
	$num = count($plugin_conf->sql);
	for ($i=0; $i<$num; $i++) {
		
	//trigger_error("keke: $num");
		
		if ($class == $plugin_conf->sql[$i]['class']) {
			$sql = str_replace("**row_id**", mysql_real_escape_string($id), $plugin_conf->sql[$i]);
			
			$res = mysql_query($sql, $jp_dbdata_conn);
			//$res = dbmain($sql);
			if ($res) {
			
			
				$num = count($plugin_conf->sql);
				
				for ($i=0; $i<$num; $i++) {
					
					if ($class == strval($plugin_conf->sql[$i]['class'])) {
			
						if($value == 0){
							$c = (isset($plugin_conf->sql[$i]['img1'])) ? addslashes($plugin_conf->sql[$i]['img1']) : "/images/icons/star_icon.gif";
							$d = '<image src="'.$c.'" border="0">';
							$c = (isset($plugin_conf->sql[$i]['sprite1'])) ? addslashes($plugin_conf->sql[$i]['sprite1']) : "";
							if ($c) $d = '<div class="'.$c.'" />';
						}
						else{ 
							$c = (isset($plugin_conf->sql[$i]['img0'])) ? addslashes($plugin_conf->sql[$i]['img0']) : "/images/icons/star_icon_faded.gif";
							$d = '<image src="'.$c.'" border="0">';
							$c = (isset($plugin_conf->sql[$i]['sprite0'])) ? addslashes($plugin_conf->sql[$i]['sprite0']) : "";
							if ($c) $d = '<div class="'.$c.'" />'; 
					}
				}
				
			}
			
			//trigger_error("'".$id.":".$class.":".$d."'");
					return "'".$id.":".$class.":".$d."'";
			}
			else {
					return "0";
			}
		}
	}

return "0";	
}



?>