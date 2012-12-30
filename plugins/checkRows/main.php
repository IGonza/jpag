<?php

function checkRows_addjs_after_get_results() 
{
	return   ' $(".jp_checkrows").live("click", function()
			  	{	
			  		$(this).parent().parent().toggleClass("selected");
			  	});
			  	
			  	$("#jp_checkall").live("click", function()
			  		{
			  			$(".jp_checkrows").attr(\'checked\', true);
			  			$(".jp_checkrows").parent().parent().addClass("selected");
			  		}
			  	);
			  	
			  	$("#jp_uncheckall").live("click", function()
			  		{
			  			$(".jp_checkrows").attr(\'checked\', false);
			  			$(".jp_checkrows").parent().parent().removeClass("selected");
			  		}
			  	);
			  	
			  	' ;
}


function checkRows_build_buttons()
{
	return '<input id="jp_checkall" type="button" value="Check All" /> <input id="jp_uncheckall" type="button" value="UnCheck All" />';
}

function checkRows_content($data, $plugin_conf)
{

	return '<input type="checkbox" class="jp_checkrows" />';
}
?>