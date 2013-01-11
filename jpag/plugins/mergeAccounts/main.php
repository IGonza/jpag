<? // this is right before I copied flagger over to mergAccounts, to see why the function isn't being called

function mergeAccounts_content($data, $plugin_conf)
{

	if(intval($data) == 1){
		$d = '<image src="/images/icons/icon_lock_active.gif" border="0">';
		$v = 'mergedYes';
	}else{ 
		$d =  '<image src="/images/icons/icon_lock_inactive.gif" border="0">'; 
		$v = 'mergedNo';
	}
	return '<div class="mergeAccounts '.$v.'">'.$d.'</div>';

}

function mergeAccounts_addjs_after_get_results($plugin_conf)
{
	$res = '';
	$res .=
	'
	$(".mergeAccounts").click(function(){
		var value = 0;
		id = $(this).closest("tr").attr("id");
		var parts = id.split("_");
		id = parts[parts.length-1]; // get last of array
		
		//alert("clicked with row_id=" + id);
		if($(this).hasClass("mergedYes")){ value = 1; }
		
		vars = "jp_id="+id+"&plugin=mergeAccounts&v=" + value;
		//alert("vars=" + vars);
	    jp_sendRequest(vars, "jp_mergeAccounts_response");
	});
	';
	
	return $res;	
}

function mergeAccounts_updateData($plugin_conf)
{
	//global $jp_dbdata_conn;
	
	// these variables are passed from the jp_sendRequest() above.
	$id = isset($_GET['jp_id']) ? trim($_GET['jp_id']) : "";
	$value = isset($_GET['v']) ? intval($_GET['v']) : "";
	$class = isset($_GET['class']) ? $_GET['class'] : "";
	
	// this function is necessary to run server-side scripting
	//return "1";
	return "'$id:$value'";
	
}

function mergeAccounts_addjs_functions($plugin_conf)
{
	$res = '
	function jp_mergeAccounts_response(data)
	{
		//alert("data: " +data);
		$("#status_indicator").attr("style", "visibility:hidden");
		var jp_flagger_d = data.split(":");
		var recID = jp_flagger_d[0];
		var currentValue = jp_flagger_d[1];
		var jp_flagger_el = $("[id$=\'"+ recID +"\']").children("td").find("div.mergeAccounts");
		
		var lockON = "<div class=\"mergeAccounts mergedYes\"><img src=\"/images/icons/icon_lock_active.gif\" border=\"0\"></div>";
		var lockOFF = "<div class=\"mergeAccounts mergedNo\"><img src=\"/images/icons/icon_lock_inactive.gif\" border=\"0\"></div>";
		
		if(currentValue == 0){ var lockICON = lockON; }
		if(currentValue == 1){ var lockICON = lockOFF; }
		
		jp_flagger_el.html(lockICON);
	}
	';
	return $res;
}


?>