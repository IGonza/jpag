<?php

function updateRows_addjs_functions($plugin_conf)
{
	
	$res = '
function jp_updateRows(ob) 
{
	
';		
	if (isset($plugin_conf->confirm) && ($plugin_conf->confirm == "1")) $res .= '
	var answer = confirm("Are you sure that you want to permanently delete a row?");
	if (answer)
	{';
	$res .= '
		updateRows_id = $(ob).parent().parent().attr("id");
		var parts = updateRows_id.split("_");
		vars = "ids=" + parts[parts.length-1] + "&plugin=updateRows";
		jp_sendRequest(vars, "jp_updateRows_parseResponse");
	';
	if (isset($plugin_conf->confirm) && ($plugin_conf->confirm == "1")) $res .= '
	}';
	$res .= '
}

function jp_updateRows_parseResponse(data)
{
	if (data == "1") {
		//alert("Row(s) deleted");
		//alert(data);
		loadPaginationTable(0);
	}
	else 
		alert("something went wrong!");
}

$("#jp_updateRowsButton").bind("click", function() {
		id = "";

';		
	if (isset($plugin_conf->confirm) && ($plugin_conf->confirm == "1")) $res .= '		
		var answer = confirm("Are you sure that you want to permanently delete selected rows?");
		
		if (answer)
		{';
	$res .= '
			$(".jp_checkrows:checked").each( function(i){
				var parts = $(this).parent().parent().attr("id").split("_");
				id = id + parts[parts.length-1] + ":";
	/*			$(this).parent().parent().children("td").wrapInner("<div></div>").children("div").slideUp(300,function() {
					$(this).remove();
				});*/
			});

			if (id != "") {
				vars = "ids=" + id + "&plugin=updateRows";
				jp_sendRequest(vars, "jp_updateRows_parseResponse");
			}';
	if (isset($plugin_conf->confirm) && ($plugin_conf->confirm == "1")) $res .= '
		}';
	$res .= '
		
});
';
	return $res;

}

function updateRows_content($data, $plugin_conf)
{
	switch ($data['type'])
	{
		case 'sprite':
			$content = '<div class="'.$data.'"></div>';
			break;
		case 'image':
			$content = '<img src="'.PLUGINS_REL.'updateRows/'.$data.'" />';
			break;
		case 'text':
		default :
			$content = $data;
			break;
	}
	return '<a href="javascript:void(0)" class="jp_updateRows" onClick="javascript:jp_updateRows(this)" >'.$content.'</a>';
}

function updateRows_updateData($plugin_conf)
{
	global $jp_dbdata_conn;
	
	$id = isset($_GET['ids']) ? trim($_GET['ids']) : "";
	$query = isset($plugin_conf->sql) ? trim($plugin_conf->sql) : "";
		
	if ($id != "" && $query != "") {
		$id = trim(str_replace(":", ",", $id), ",");
		$query = str_replace("{*id*}", mysql_real_escape_string($id), $query);
		$res = mysql_query($query, $jp_dbdata_conn);
		
		if ($res) return true;
		else return false;
	}
	else
		return true;
}

function updateRows_build_buttons($plugin_conf)
{
	global $plugin_array;
	
	if (in_array("checkRows", $plugin_array))
		return ' <input id="jp_updateRowsButton" type="button" value="Update" /> ';
	else 
		return "";
}

?>