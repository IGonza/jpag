<?php

function sortable_addjs_after_get_results($plugin_conf)
{
	global $jp_dbdata_conn;
	
	$res = "";
	
		
	$res .= '
	
	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};

	var jp_sortable = $(".jp_data tbody").sortable({
		helper: fixHelper,
		cursor: \'move\',
		update: function(event, ui) { alert(jp_sortable_serialize(this).join("|")); }
	}).disableSelection();	

	';
	
	return $res;
}

function sortable_addjs_functions()
{
	$res = "";
	
		
	$res .= '
	
	function jp_sortable_serialize(v)
	{
		return $(".jp_data tbody").sortable(\'toArray\');
	}

	';
	
	return $res;
}
?>