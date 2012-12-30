<?
// function that adds new column header .
// $col_number - column number before it to add new header
/*
function rowNumbers_add_column_header($col_number, $plugin_conf)
{
	global $jpaginate_config;
	
	$num = isset($plugin_conf->colNumber) ? intval($plugin_conf->colNumber) : 1;
	if ($num == ($col_number+1))
	{	
		$td[0]['id'] = '';
		$td[0]['class'] = '';
		$td[0]['val'] = $plugin_conf->colName;
		
		return $td;
	}
	else return false;
}



function rowNumbers_add_column($col_number, $row, $plugin_conf)
{
	$td = array();
	$num = isset($plugin_conf->colNumber) ? intval($plugin_conf->colNumber) : 1;
	if ($num == ($col_number+1))
	{	
		$td[0]['id'] = 'numbering_'.$row;
		$td[0]['class'] = 'jp_numbering';
		$td[0]['val'] = '.';
		
		return $td;
	}
	else return false;
	
}
*/

// function that adds js script to do some steps with returned data from the server
// here we need to run numbering js script
function rowNumbers_addjs_after_get_results() 
{
	return   'var numbering = $(".jp_numbering");
			  numbering.each(function(i, val)
			  	{	
			  		$(this).html((cur_page-1)*max_results+i+1);
			  	})' ;
}


function rowNumbers_content($data, $plugin_conf)
{

	return '<div class="jp_numbering"></div>';
}
?>