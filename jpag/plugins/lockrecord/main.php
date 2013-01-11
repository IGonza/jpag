<?

function lockrecord_addjs_functions($plugin_conf)
{
		$res = '
	
	function jp_lockrecord_response(data)
	{
		if (data != "0") {
			
			var jp_lockrecord_d = data.split(":");

			var jp_lockrecord_el = $("[id$=\'_"+ jp_lockrecord_d[0] +"\']").children("td").find("div."+ jp_lockrecord_d[1]).find("img");
		
			if (jp_lockrecord_el.attr("src") == "/images/icons/icon_lock_active.gif") {
				jp_lockrecord_el.attr("src", "/images/icons/icon_lock_inactive.gif");
				$("[id$=\'_"+ jp_lockrecord_d[0] +"\']").css("background-color","#FFF");
			}
			else {
				jp_lockrecord_el.attr("src", "/images/icons/icon_lock_active.gif");
				$("[id$=\'_"+ jp_lockrecord_d[0] +"\']").css("background-color","#F6CCDA");
			}
			
			$("#status_indicator").attr("style", "visibility:hidden");
		}
		
		
		else 
			alert("something went wrong!");
	}
	
	';
	return $res;
}

function lockrecord_addjs_after_get_results($plugin_conf)
{
	$res = "";
	
	
	$num = isset($plugin_conf->sql) ? count($plugin_conf->sql) : 0;
	for ($i = 0; $i<$num; $i++)
	{
	
	$res .=
	'
	$(".lockrecord").each(function(){
			
			var imgstatus = $(this).closest("tr").children("td").find("img");
			if (imgstatus.attr("src") == "/images/icons/icon_lock_active.gif") {
			 $(this).closest("tr").css("background-color","#F6CCDA");
			}
			
			$(this).click(function(){
				
				id = $(this).closest("tr").attr("id");
				var parts = id.split("_");
				id = parts[parts.length-1]; // get last of array
				
				//alert("flag clicked with row_id=" + id);

				vars = "jp_id="+id+"&plugin=lockrecord&class='.$plugin_conf->sql[$i]['class'].'";
				jp_sendRequest(vars, "jp_lockrecord_response");
			
			});
			
	});
	';
	}
	
	return $res;	
}


function lockrecord_updateData($plugin_conf)
{
	global $jp_dbdata_conn;
	
	$id = isset($_GET['jp_id']) ? trim($_GET['jp_id']) : "";
	
	if (isset($_GET['class'])) $class = $_GET['class'];
	else $class = "";
	
	$num = count($plugin_conf->sql);
	for ($i=0; $i<$num; $i++) {
		if ($class == $plugin_conf->sql[$i]['class']) {
			$sql = str_replace("**row_id**", mysql_real_escape_string($id), $plugin_conf->sql[$i]);
			
			$res = mysql_query($sql, $jp_dbdata_conn);
			if ($res) {
					return "'".$id.":".$class."'";
			}
			else {
					return "0";
			}
		}
	}

	
}


function lockrecord_content($data, $plugin_conf)
{

	$class = isset($data['class']) ? $data['class'] : "";
	
	if(intval($data) == 1){$d = '<image src="/images/icons/icon_lock_active.gif" border="0">';}
	else{ $d =  '<image src="/images/icons/icon_lock_inactive.gif" border="0">'; }
	
	return '<div class="'.$class.'">'.$d.'</div>';

}

?>
