<?php

function deleteRow_addjs_functions($plugin_conf)
{
	
	$res = '
function jp_deleteRow(ob) 
{
	
';		
	if (isset($plugin_conf->confirm) && ($plugin_conf->confirm == "1")) $res .= '
	var answer = confirm("Are you sure that you want to permanently delete a row?");
	if (answer)
	{';
	$res .= '
		deleteRow_id = $(ob).parent().parent().attr("id");
		vars = "ids=" + deleteRow_id + "&plugin=deleteRow";
		jp_sendRequest(vars, "jp_deleteRow_parseResponse");
	';
	if (isset($plugin_conf->confirm) && ($plugin_conf->confirm == "1")) $res .= '
	}';
	$res .= '
}

function jp_deleteRow_parseResponse(data)
{
	if (data == "1") {
		//alert("Row(s) deleted");
		loadPaginationTable(0);
	}
	else 
		alert("something went wrong!");
}

$("#jp_deleteRowButton").bind("click", function() {
		id = "";

';		
	if (isset($plugin_conf->confirm) && ($plugin_conf->confirm == "1")) $res .= '		
		var answer = confirm("Are you sure that you want to permanently delete selected rows?");
		
		if (answer)
		{';
	$res .= '
			$(".jp_checkrows:checked").each( function(i){
				id = id + $(this).parent().parent().attr("id") + ":";
	/*			$(this).parent().parent().children("td").wrapInner("<div></div>").children("div").slideUp(300,function() {
					$(this).remove();
				});*/
			});

			if (id != "") {
				vars = "ids=" + id + "&plugin=deleteRow";
				jp_sendRequest(vars, "jp_deleteRow_parseResponse");
			}';
	if (isset($plugin_conf->confirm) && ($plugin_conf->confirm == "1")) $res .= '
		}';
	$res .= '
		
});
';
	return $res;

}

function deleteRow_content($data, $plugin_conf)
{
	switch ($data['type'])
	{
		case 'image':
			$content = '<img src="'.PLUGINS_REL.'deleteRow/'.$data.'" />';
			break;
		case 'text':
		default :
			$content = $data;
			break;
	}
	return '<a href="javascript:void(0)" class="jp_deleteRow" onClick="javascript:jp_deleteRow(this)" >'.$content.'</a>';
}

function deleteRow_updateData($plugin_conf)
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
		return false;
}

function deleteRow_build_buttons($plugin_conf)
{
	global $plugin_array;
	
	if (in_array("checkRows", $plugin_array))
		return '<input id="jp_deleteRowButton" type="button" value="Delete Checked" /> ';
	else 
		return "";
}

?>
