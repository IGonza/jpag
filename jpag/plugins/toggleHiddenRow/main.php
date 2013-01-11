<?

/*function toggleHiddenRow_addjs_functions($plugin_conf)
{
		$res = '
	
	function jp_flagger_response(data)
	{
		if (data != "0") {
			
			var jp_flagger_d = data.split(":");

			var jp_flagger_el = $("[id$=\'"+ jp_flagger_d[0] +"\']").children("td").find("div."+ jp_flagger_d[1]);
			
			jp_flagger_el.html(jp_flagger_d[2]);
			
			if (jp_flagger_el.hasClass("jp_fl0"))
				jp_flagger_el.removeClass("jp_fl0").addClass("jp_fl1");
			else
				jp_flagger_el.removeClass("jp_fl1").addClass("jp_fl0");
			
			
			
			$("#status_indicator").attr("style", "visibility:hidden");
		}
		else 
			alert("something went wrong!");
	}
	
	';
	return $res;
}*/

function toggleHiddenRow_addjs_after_get_results($plugin_conf)
{
	$res = "";
	
	
	$num = isset($plugin_conf->class) ? count($plugin_conf->class) : 0;
	for ($i = 0; $i<$num; $i++)
	{
		$content = (isset($plugin_conf->class[$i]['content'])) ? addslashes($plugin_conf->class[$i]['content']) : "content";
		$text0 = (isset($plugin_conf->class[$i]['text0'])) ? addslashes($plugin_conf->class[$i]['text0']) : "";
		$text1 = (isset($plugin_conf->class[$i]['text1'])) ? addslashes($plugin_conf->class[$i]['text1']) : "";
	
		$thinker = (isset($plugin_conf->class[$i]['thinker_icon'])) ? addslashes($plugin_conf->class[$i]['thinker_icon']) : "/images/loaders/ajax-loader-blue.gif";
		$thinker = '<image src=\"'.$thinker.'\" border=\"0\" />';
		
		$closed = (isset($plugin_conf->class[$i]['img0'])) ? addslashes($plugin_conf->class[$i]['img0']) : "/images/icons/star_icon.gif";
		$closed = '<image src="'.$closed.'" border="0">'.$text0;
		$c = (isset($plugin_conf->class[$i]['sprite0'])) ? addslashes($plugin_conf->class[$i]['sprite0']) : "";
		if ($c) $closed = '<div class="'.$c.'" />'.$text0;
		
		$open = (isset($plugin_conf->class[$i]['img1'])) ? addslashes($plugin_conf->class[$i]['img1']) : "/images/icons/star_icon.gif";
		$open = '<image src="'.$open.'" border="0">'.$text1;
		$c = (isset($plugin_conf->class[$i]['sprite1'])) ? addslashes($plugin_conf->class[$i]['sprite1']) : "";
		if ($c) $open = '<div class="'.$c.'" />'.$text1;
		
		//$url = (isset($plugin_conf->class[$i]['url'])) ? addslashes($plugin_conf->class[$i]['url']) : "";
	
	$res .=
	'
	$(".'.strval($plugin_conf->class[$i]).'").each(function(){
			$(this).click(function(){
				
				var tr = $(this).closest("tr");
				id = $(tr).attr("id");
				var parts = id.split("_");
				id = parts[parts.length-1]; // get last of array
				
				var tdsnum = $(tr).children().length;
				
				var url = $(this).parent().find("div").html();
				
				if ( $(tr).next("tr").hasClass("jp_hiddenRow")  )
				{
					$(this).html("'.addslashes($closed).'");
					$(tr).next("tr").remove();
				}
				else
				{
					$(tr).after("<tr class=\"jp_hiddenRow\"><td colspan=\"" + tdsnum + "\">'.$thinker.'</td></tr>");
					$(this).html("'.addslashes($open).'");
					';
	if ($content == "url")
	{
			$res .= '
					$.ajax({
						data:"val="+window.location.hash,
						url:  $("<div/>").html(url).text(),
						success:function(response){$(tr).next("tr").find("td").html(response);} 
					});
					';
	}
	elseif ($content == "content")
	{
			$res .= '$(tr).next("tr").find("td").html(url);';
	}
		
	$res .= '
			//$(tr).next("tr").find("td").load(url);
					
				}
			
			});
			
	});
	';
	}
	
	return $res;	
}



function toggleHiddenRow_content($data, $plugin_conf)
{

	$d = "";
	$class = isset($data['class']) ? $data['class'] : "";
	 
	$num = count($plugin_conf->class);
	for ($i=0; $i<$num; $i++) {
		if ($class == strval($plugin_conf->class[$i])) {
			
			$content = (isset($plugin_conf->class[$i]['content'])) ? addslashes($plugin_conf->class[$i]['content']) : "content";
			$text0 = (isset($plugin_conf->class[$i]['text0'])) ? addslashes($plugin_conf->class[$i]['text0']) : "";
			
			$c = (isset($plugin_conf->class[$i]['img0'])) ? addslashes($plugin_conf->class[$i]['img0']) : "/images/icons/star_icon.gif";
			$d = '<image src="'.$c.'" border="0">'.$text0;
			$c = (isset($plugin_conf->class[$i]['sprite0'])) ? addslashes($plugin_conf->class[$i]['sprite0']) : "";
			if ($c) $d = '<div class="'.$c.'" />'.$text0;
			$v = 1;
			
			$formatFunction = (isset($plugin_conf->class[$i]['formatFunction'])) ? addslashes($plugin_conf->class[$i]['formatFunction']) : "";
			if (!empty($formatFunction) && function_exists($formatFunction))
			{
				$data = call_user_func($formatFunction, strval($data));
			}
			
			/*if ($content == "url") {
				$data = html_entity_decode($data);
				var_dump(strval($data));
			}*/


		}
	}
	

	return '<div style="display:none">'.strval($data).'</div><div class="'.$class.'">'.$d.'</div>';

}

?>