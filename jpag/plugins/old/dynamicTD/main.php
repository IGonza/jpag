<?

function dynamicTD_content($data, $plugin_conf)
{
	$class = isset($data['class']) ? $data['class'] : ""; // this is part of the <content> ?
	
	$separated = explode("|",$data);
	if($separated[0]==''){ return "missing mainID"; }else{ $mainID = $separated[0]; }
	if($separated[1]==''){ return "missing msg"; }else{ $msg = $separated[1]; }
	if($separated[2]==''){ $icon = ""; }else{ $icon = $separated[2]; }
	
	$showtext = '<div class="divlink1L '.$icon.'"></div><div class="divlink2L clickDynamicTD" id="'.rand().'">'.$msg.'</div>'; 
	return $showtext;
}

function dynamicTD_addjs_after_get_results($plugin_conf)
{
	$res =
	'
	$(".clickDynamicTD").click(function(){
		var cell = $(this).closest("td"); 
		var parenttr = $(this).closest("tr").attr("id");
		var brokenstring=parenttr.split("_");
		var mainID = brokenstring[1];  //var mainID = brokenstring[brokenstring.length-1];
		//alert("mainID=" + mainID); return false;
		if(mainID==\'\'){alert("mainID is missing");return false;}
		$($(this).parent()).html(\'<img src="images/loaders/ajax-loader-red.gif" alt="loading..." />\'); 
		$.ajax({data:"formId=dynamicTD&mainID="+mainID,success:function(response){eval(response);}});
		return false;
	});
	';
	return $res;	
}


?>
