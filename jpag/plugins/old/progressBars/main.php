<?php 

function progressBars_content($data, $plugin_conf)
{
	//$class = isset($data['class']) ? trim($data['class']) : "";
	$content = explode("|", $data);
	//$content[1] = (isset($content[1])) ? $content[1] : "";
	return '<div class="progressBar">'.$content[0].'</div>';
}


function progressBars_addjs_after_get_results($plugin_conf)
{
	$res = "";
	$res .=
	'
	$(".progressBar").each(function(){
		$(this).progressBar();
	});
	';
	
	return $res;	
}
?>