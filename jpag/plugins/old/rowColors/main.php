<?
// function that adds js script to do some steps with returned data from the server
// here we need to run numbering js script
function rowColors_addjs_after_get_results() 
{
	return   'var numbering = $(".jp_numbering");
			  numbering.each(function(i, val)
			  	{	
			  		$(this).html((cur_page-1)*max_results+i+1);
			  	})' ;
}


function rowColors_content($data, $plugin_conf)
{

	return '<div class="jp_numbering"></div>';
}
?>