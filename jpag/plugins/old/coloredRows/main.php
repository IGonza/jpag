<?php 

/*
<plugin>
	<name>coloredRows</name>
	<RandomAttribute>queuestatus</RandomAttribute><!-- the sql column to compare against (must exist in the query, and part of tablerowRandomAttributes child tags) -->
	<rowcolors>1:FFFFFF,2:F6CCDA</rowcolors><!-- use hex colors -->
</plugin>
*/

function coloredRows_addjs_after_get_results($plugin_conf)
{

	//<RandomAttribute>queuestatus</RandomAttribute>
	$RandomAttribute = !empty($plugin_conf->RandomAttribute) ? trim($plugin_conf->RandomAttribute) : "rand1"; //if no RandomAttribute tab is specified, it assumes it's always the first: rand1="???"
	
	//<rowcolors>1:FFFFFF,2:F6CCDA</rowcolors>
	$rowcolors = !empty($plugin_conf->rowcolors) ? trim($plugin_conf->rowcolors) : "";

	$res=',$(".jp_data tr").each(function(i){';
		// does the attr exist?
			$res .= 'var attr = $(this).attr("'.$RandomAttribute.'");';
			$res .= "if (typeof attr !== 'undefined' && attr !== false){";

				if(!empty($rowcolors)){
					$options = explode(',',$rowcolors);
					foreach($options as $val){
						$exploded = explode(':',$val);
						$attrvalue = $exploded[0];
						$trcolor = $exploded[1];
						$res .= 'if(attr == "'.$attrvalue.'") $(this).css("background-color","#'.$trcolor.'");';
					}
					
				}
			$res .= '}';
			
	$res .= '});';

	return $res;
}

?>