<?php
function jpaginate_include_head()
{
	global $jpaginate_config;
	
	//echo "<link href=\"".JPAG_LOCATION."jpaginate.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
	//echo "<link href=\"".JPAG_CSS."\" rel=\"stylesheet\" type=\"text/css\" />\n";

	if ( (!isset($jpaginate_config->loadJQUERY) || $jpaginate_config->loadJQUERY == "true") && JPAG_JQUERY != "")
			echo "<script type=\"text/javascript\" src=\"".JPAG_JQUERY."\"></script>\n";
	//echo md5_file(CONFIG.JPAGINATE_CONFIG)."<br>";
	//echo md5_file("/apache/html/jpaginate.com/pagination3/jpaginate/configure/configure.xml");
	echo "<script type=\"text/javascript\" src=\"".SERVER_FILE."?load=js&f=".md5(JP_CONFIG_CONTENT)."&".$_SERVER['QUERY_STRING']."\"></script>\n";
	echo jp_hook_include_head();
}

function jpaginate_start()
{
	global $jpaginate_config;
	
	if (file_exists(TEMPLATES.JPAGINATE_TEMPLATE)) 
	{
	
		$tpl = file_get_contents(TEMPLATES.JPAGINATE_TEMPLATE);
		$tpl = str_replace("{*JPAG_IMAGES*}", JPAG_IMAGES, $tpl);
		
		ob_start();
		jpaginate_build_filters();
		$filters = ob_get_contents();
		ob_end_clean();
		$tpl = str_replace("{*filters*}", $filters, $tpl);
		unset($filters);
		
		ob_start();
		jpaginate_build_maxResultBox();
		$maxResultBox = ob_get_contents();
		ob_end_clean();
		$tpl = str_replace("{*maxResultBox*}", $maxResultBox, $tpl);
		unset($maxResultBox);
		
		ob_start();
		jpaginate_build_buttons();
		$buttons = ob_get_contents();
		ob_end_clean();
		$tpl = str_replace("{*buttons*}", $buttons, $tpl);
		unset($buttons);
		
		echo $tpl;
	
	}
	else 
	{
		
		die("template not found");
	
	}
	
	echo "<input type=\"hidden\" id=\"jp_curpage\" value=\"1\">";
	
	
	$sort_column = "";
	$sort_d = "";
	
	if (isset($jpaginate_config->default_sort)) {
		$sort = explode(",", $jpaginate_config->default_sort);
		
		$sort_column = intval($sort['0']);
		if (isset($sort['1'])) {
			if (trim(strtolower($sort['1'])) == "desc" || trim(strtolower($sort['1'])) == "asc")  
				$sort_d = trim(strtolower($sort['1']));
		}
	}
	echo "<input type=\"hidden\" id=\"jp_sort\" value=\"".$sort_column."\">";
	echo "<input type=\"hidden\" id=\"jp_sortd\" value=\"".$sort_d."\">";
	
	
	echo "<input type=\"hidden\" id=\"jp_gets\" value=\"".$_SERVER['QUERY_STRING']."\">";

}


function jpaginate_build_filters()
{
	global $jpaginate_config, $jp_dbdata_conn;

	if (isset($jpaginate_config->filters) && (!empty($jpaginate_config->filters->filter) || !empty($jpaginate_config->filters->multifilter)) )
	{
		
		if (isset($jpaginate_config->filters['visible']) && empty($jpaginate_config->filters['visible']) )
			echo "<div style=\"display:none\">";
		
		$num =  count($jpaginate_config->filters->filter);
		
		for($i = 0; $i < $num; $i++) {
		
			
			if (isset($jpaginate_config->filters->filter[$i]['visible']) && empty($jpaginate_config->filters->filter[$i]['visible']) )
				echo "<div style=\"display:none\">";
			
			$style = "";
			if (isset($jpaginate_config->filters->filter[$i]->style) && !empty($jpaginate_config->filters->filter[$i]->style)) {
				$style = ' style="'.trim($jpaginate_config->filters->filter[$i]->style).'" ';
			}
			switch ($jpaginate_config->filters->filter[$i]->type) {
				case "searchbox" :
					$id = "jp_string_search_".$i;
					$defVal = (isset($jpaginate_config->filters->filter[$i]->defaultValue)) ? $jpaginate_config->filters->filter[$i]->defaultValue : "";
					
					if (isset($jpaginate_config->filters->filter[$i]->autosubmit) && !empty($jpaginate_config->filters->filter[$i]->autosubmit)) 
						$action = "onkeyup=\"changeSearchCriteria()\"";
					else
						$action = "";
						
					if (isset($jpaginate_config->filters->filter[$i]->focused) && !empty($jpaginate_config->filters->filter[$i]->focused)) 
						$cl = " focused";
					else
						$cl = "";
					
					if (isset($jpaginate_config->filters->filter[$i]->title)) 
						echo "<span class=\"jp_filter_title\">".htmlentities($jpaginate_config->filters->filter[$i]->title)."</span>";
					echo "<input type=\"text\" class=\"jp_filter".$cl."\" ".$action." id=\"".$id."\" value=\"".htmlentities($defVal)."\" ".$style." />&nbsp;";
					break;
				case "menu" :
					if (isset($jpaginate_config->filters->filter[$i]->title)) 
						echo "<span class=\"jp_filter_title\">".htmlentities($jpaginate_config->filters->filter[$i]->title)."</span>";
						
					$id = "jp_menu_filter_".$i;
					
					$defVal = (isset($jpaginate_config->filters->filter[$i]->defaultValue)) ? $jpaginate_config->filters->filter[$i]->defaultValue : "";
					
					if (isset($jpaginate_config->filters->filter[$i]->autosubmit) && !empty($jpaginate_config->filters->filter[$i]->autosubmit)) 
						$action = "onChange=\"changeSearchCriteria()\"";
					else
						$action = "";
						
					if (isset($jpaginate_config->filters->filter[$i]->focused) && !empty($jpaginate_config->filters->filter[$i]->focused)) 
						$cl = " focused";
					else
						$cl = "";

					if (isset($jpaginate_config->filters->filter[$i]->type['multiple']) && intval($jpaginate_config->filters->filter[$i]->type['multiple'])>0) 
						$mp = " multiple='multiple' size='".intval($jpaginate_config->filters->filter[$i]->type['multiple'])."'";
					else
						$mp = "";
					
					echo "<select class=\"jp_filter".$cl."\" id=\"".$id."\" ".$action." ".$style." ".$mp.">";
					echo "<option value=\"\">-----</option>";
					if (isset($jpaginate_config->filters->filter[$i]->values))
					{
						$values = explode("|", $jpaginate_config->filters->filter[$i]->values);
						foreach ($values as $val)
						{
							list($v,$name) = explode(":", $val);
							$selected = "";
							if ($v == $defVal) $selected = " selected=\"selected\" ";
							echo "<option value=\"".$v."\" ".$selected.">".htmlentities($name)."</option>";
						}
					}
					elseif (isset($jpaginate_config->filters->filter[$i]->mysqlValues))
					{
						$res = mysql_query($jpaginate_config->filters->filter[$i]->mysqlValues, $jp_dbdata_conn) or die(mysql_error());
						while ($row = mysql_fetch_assoc($res)) 
						{
							$selected = "";
							if ($row['val'] == $defVal) $selected = " selected=\"selected\" ";
							echo "<option value=\"".$row['val']."\" ".$selected.">".htmlentities($row['name'])."</option>";
						}
					}
					echo "</select>&nbsp;";
				default:
					break;
			}
			
			if (isset($jpaginate_config->filters->filter[$i]['visible']) && empty($jpaginate_config->filters->filter[$i]['visible']) )
				echo "</div>";
		}
		
		
		if (isset($jpaginate_config->filters['visible']) && empty($jpaginate_config->filters['visible']) )
			echo "</div>";
			
		
			// multifilters
		
		$num =  count($jpaginate_config->filters->multifilter);


		if ($num)
		{
			$id = "jp_menu_multifilter_0";
			$defVal = (isset($jpaginate_config->filters->multifilter->defaultValue)) ? $jpaginate_config->filters->multifilter->defaultValue : "";
			echo "<select class=\"jp_multifilter\" id=\"".$id."\" >";
			//echo "<option value=\"\">-----</option>";
					
			if (count($jpaginate_config->filters->multifilter->fieldName))
			{
					$values = $jpaginate_config->filters->multifilter->fieldName;
					$ii = 0;
					foreach ($values as $val)
					{
							list($v,$name) = explode(":", $val->field);
							$selected = "";
							if ($ii == $defVal) $selected = " selected=\"selected\" ";
							echo "<option value=\"".$ii."\" ".$selected.">".htmlentities($name)."</option>";
							$ii++;
					}
			}
					
			echo "</select>&nbsp;";
			echo "<input id=\"jp_multifilter_0\" class=\"jp_multifilter\" style=\"width:250px;\" />";
		}
		

		// end multifilters
		
		// search button
		if (isset($jpaginate_config->filters->button)) {
			$val = (!empty($jpaginate_config->filters->button)) ? $jpaginate_config->filters->button : "Search";
			echo "<input type=\"button\" value=\"".$val."\" onClick=\"changeSearchCriteria()\" />";
		}
	}
}


function jpaginate_build_maxResultBox()
{
	global $jpaginate_config;
	
	$showing = isset($jpaginate_config->text->showing) ? ($jpaginate_config->text->showing) : "";
	$of = isset($jpaginate_config->text->of) ? ($jpaginate_config->text->of) : "";
	$total_results = isset($jpaginate_config->text->total_results) ? ($jpaginate_config->text->total_results) : "";
	
	echo "<span class='jp_showing_text'>".$showing."</span><span id='jp_max_results_span'>
              <select name=\"jp_max_results\" id=\"jp_max_results\" onchange=\"changeMaxResults(this.value)\">";

	if (isset($jpaginate_config->maxResults->number) )
	{
		$default =  (isset($jpaginate_config->pagination->pageTotal)) ? $jpaginate_config->pagination->pageTotal : "10";
		$num =  count($jpaginate_config->maxResults->number);
		
		for($i = 0; $i < $num; $i++) {
			if ($default == intval($jpaginate_config->maxResults->number[$i])) $selected = " selected=\"selected\" ";
			else $selected = "";
			echo "<option value=\"".intval($jpaginate_config->maxResults->number[$i])."\" ".$selected.">".intval($jpaginate_config->maxResults->number[$i])."</option>\n";

		} 
	}
	
	echo "</select></span> <span class='jp_of_text'>".$of."</span> <span id=\"jp_total_rows\">0</span><span class='jp_total_results_text'>".$total_results."</span>";
}

function jpaginate_build_buttons()
{
	global $jpaginate_config;
	
	$res = "";
	
	
	$res .= jp_hook_build_buttons();
	
	if (!empty($res)) 
		$res = "<div id=\"jpaginate_buttons\">".$res."</div>";
	
	echo $res;
}

function jpaginate_loadData()
{
	global $jpaginate_config, $jp_dbdata_conn;

	$current_page = (isset($_GET['cur_page'])&&(intval($_GET['cur_page'])!="0")) ? intval($_GET['cur_page']) : 1;
	$search_val = (isset($_GET['search_val'])&&($_GET['search_val']!="")) ? $_GET['search_val'] : "";
	$multisearch_val = (isset($_GET['multisearch_val'])&&($_GET['multisearch_val']!="")) ? $_GET['multisearch_val'] : "";
	
	$search_query = trim(jpaginate_getSearchWhereSQL($search_val, $multisearch_val));
	
	if (strpos($search_query, "SELECT") === 0)
	{
		$sql = $search_query;
	} 
	else 
	{
		$sql = $jpaginate_config->mainSQL.jpaginate_getSearchWhereSQL($search_val, $multisearch_val);
	}

if (defined('JP_DEBUG') && JP_DEBUG == TRUE) $time_start = microtime(true);

	$from_pos = stripos($sql,"FROM");
	$select_part = substr($sql,0,$from_pos);
	$distinct_pos = stripos($select_part,"DISTINCT");
	if ($distinct_pos === FALSE)
	{
		$count_sql = "SELECT count(*) ".substr($sql, $from_pos);
	}
	else 
	{
		$comma_pos = strpos($select_part, "," ,$distinct_pos);
		if ($comma_pos === FALSE)
			$distinct_content = trim(substr($select_part, $distinct_pos));
		else 
			$distinct_content = trim(substr($select_part, $distinct_pos, $comma_pos-$distinct_pos));
		
		$count_sql = "SELECT count(".$distinct_content.") ".substr($sql, $from_pos);
		
	}

if (defined('JP_DEBUG') && JP_DEBUG == TRUE) echo "<b>Count sql:</b> ".$count_sql."<br>";
	
	//$mysql_res = mysql_query($sql, $jp_dbdata_conn) or die(mysql_error());
	$mysql_res = mysql_query($count_sql, $jp_dbdata_conn) or die(mysql_error());
	$r = mysql_fetch_row($mysql_res);
	$totalRows = $r[0];

	//$totalRows = mysql_num_rows($mysql_res);
//	$totalRows = 1100000;

	$sql .= jpaginate_getSortSQL();

	$sql .= jpaginate_getLimitSQL($current_page);
	
if (defined('JP_DEBUG') && JP_DEBUG == TRUE) echo "<b>Main sql:</b> ".$sql; 

	$result = mysql_query($sql, $jp_dbdata_conn)  or die(mysql_error());

if (defined('JP_DEBUG') && JP_DEBUG == TRUE) 
{
	$time_end = microtime(true);

	echo "<br><b>Total queries time:</b> ";
	echo $time = $time_end - $time_start;
	echo "<br>";
}

	$total_pages = ceil($totalRows / $_GET['max_results']);
	
	$display = "";
	
	
	$listRows    = jpaginate_buildTableRows($result);
	$listHeaders = jpaginate_buildTableHeaders();
	$colGroups   = jpaginate_buildTableColGroups();
	
	$display_paginate = "";
	if ($totalRows)
	{
		$display_paginate = jpaginate_buildPageNumbering($current_page, $total_pages);

		$display = '
		<form name="pagination_form">
	    <table class="jp_data" cellpadding="0" cellspacing="0">'.$colGroups.'
	        <thead><tr>';
	    $display .= $listHeaders;
	    $display .= "</tr></thead>";
    
    
    	$display .= $listRows;
    
	    $display .= "</table>
    	</form>";
    }
	
	if (empty($display)) $display = isset($jpaginate_config->text->no_results) ? $jpaginate_config->text->no_results : "no results are present";
	
	return $display."|||||".$totalRows."|||||".$display_paginate;
}


function jpaginate_loadJS($f_id)
{
	global $jpaginate_config, $jp_dbmain_conn;
	
	$res = mysql_query("SELECT `content` FROM `js_files` WHERE `id` = '".mysql_real_escape_string($f_id)."' ", $jp_dbmain_conn);
	if (mysql_num_rows($res)) {
		$res = mysql_fetch_assoc($res);
		echo $res['content'];
	}
	else 
	{
		jpaginate_buildJS($f_id);
		jpaginate_loadJS($f_id);
	}
	
}

function jpaginate_buildJS($f_id)
{
	global $jpaginate_config, $jp_dbmain_conn, $plugin_num, $plugin_array;
	
	/*$cnf_array = array();
	$d = dir(CONFIG);

	while (false !== ($entry = $d->read())) {
	   if ($entry!=".." && $entry!="." && md5_file(CONFIG.$entry) == $f_id )
	   {*/
	   		$jpaginate_config = loadConfig(CONFIG.JPAGINATE_CONFIG);
	   		require_once(JPAG_FUNCTIONS."plugins.php");
	   /*}
	}*/
	
	ob_start();

	require_once(JPAG_FUNCTIONS."jpaginate.js.php");
	mysql_query("INSERT INTO js_files(id, content) VALUES ('".mysql_real_escape_string($f_id)."', '".mysql_real_escape_string(ob_get_contents())."') ", $jp_dbmain_conn);

	ob_end_clean();
	
	//$d->close();
}



function jpaginate_getSearchWhereSQL($search_val, $multisearch_val)
{
	global $jpaginate_config;
	
	$search_where = " ";
	
	
	$values = explode("|||", $search_val);
	
	if (!empty($values))
	{	
		if (isset($jpaginate_config->filters) && !empty($jpaginate_config->filters->filter) )
		{
			$num =  count($jpaginate_config->filters->filter);
		
			for($i = 0; $i < $num; $i++) {
				if (isset($values[$i]) && $values[$i]!="") 
				{
					switch ($jpaginate_config->filters->filter[$i]->type) {
						case "searchbox" :
							$n = count($jpaginate_config->filters->filter[$i]->fieldName);
							for($j=0;$j<$n;$j++)
							{
								$art = ($j>0) ? " OR " : " AND ( ";
								if (isset($jpaginate_config->filters->filter[$i]->compare))
									$compare = $jpaginate_config->filters->filter[$i]->compare; 
								else 
									$compare = "like";
									
								if (isset($jpaginate_config->filters->filter[$i]->applyFunction))
								{
									$applyFunction = trim($jpaginate_config->filters->filter[$i]->applyFunction);
								}
								else
								{
									$applyFunction = "";
								}
								
								
								switch ($compare)
								{
									case 'more':
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." > '".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."'";
										break;
									case 'less':
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." < '".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."'";
										break;
									case 'equal':
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." = '".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."'";
										break;
									case 'like':
									default: 
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." LIKE '%".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."%'";
										break;
								}
								
								//$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." LIKE '%".mysql_real_escape_string($values[$i])."%'";
								if ($j==$n-1) $search_where .= ")";
							}
							break;
						case "menu" :
							/*$allow = false;
							if (isset($jpaginate_config->filters->filter[$i]->values)) {
								// here we will chaeck if user sends allowed value and doesn't try to send fake value
								$allwedVal = array();
								$allowedValues = explode("|", $jpaginate_config->filters->filter[$i]->values);
								foreach ($allowedValues as $val) {	
									list($v,$name) = explode(":", $val);
									$allowedVal[] = $v;
								}
								
								if (in_array($values[$i], $allowedVal))	
									$allow = true;
							}
							elseif (isset($jpaginate_config->filters->filter[$i]->mysqlValues)) {
								$allow = true;
							}
							
							if ($allow == true) */
							if (isset($jpaginate_config->filters->filter[$i]->type['multiple']))
							{
								if ($values[$i]!='null')
									$search_where .= " AND ".mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName)." IN ('".str_replace(",", "','",mysql_real_escape_string($values[$i]))."') ";
							}
							else
								$search_where .= " AND ".mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName)." = '".mysql_real_escape_string($values[$i])."'";
							break;
						default:
							break;
					}
				}
			}
		}
	}
	
	
	$multi_values = explode("|||", $multisearch_val);

	if (!empty($multi_values))
	{	
		if (isset($jpaginate_config->filters) && !empty($jpaginate_config->filters->multifilter) )
		{
			$num =  count($jpaginate_config->filters->multifilter);
		
			/*for($i = 0; $i < $num; $i++) {
				if (isset($multi_values[$i*2]) && $multi_values[$i*2]!="" && isset($multi_values[$i*2+1]) && $multi_values[$i*2+1]!="") 
				{
					switch ($jpaginate_config->filters->multifilter[$i]->type) {
						case "menu" :
							$allow = false;
							if (isset($jpaginate_config->filters->multifilter[$i]->fieldName)) {
								// here we will check if user sends allowed value and doesn't try to send fake value
								$multi_fields = array();
								$allowedValues = $jpaginate_config->filters->multifilter[$i]->fieldName;

								for ($j=0; $j<count($allowedValues); $j++)
								{
									list($v,$name) = explode(":", $allowedValues[$j]->field);
									$multi_fields[$j] = $v;
								}
					
								//if (in_array($values[$i], $allowedVal))	$allow = true;
								if (count($multi_fields) >= $i) $allow = true;
							}
							else {
								break;
							}
							
							$value = intval($multi_values[$i*2]);
							if ($allow == true) 
								$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." LIKE '%".mysql_real_escape_string($multi_values[$i*2+1])."%'";
							
							if (isset($jpaginate_config->filters->multifilter[$i]->fieldName[$value]->sql))
							{
								$search_where = $jpaginate_config->filters->multifilter[$i]->fieldName[$value]->sql.' '.$search_where;
							}
								
							break;
						default:
							break;
					}
				}
			}*/
			if ($num)
			{
				if (isset($multi_values[0]) && $multi_values[0]!="" && isset($multi_values[1]) && $multi_values[1]!="") 
				{
						$allow = false;
						if (isset($jpaginate_config->filters->multifilter->fieldName)) {
							// here we will check if user sends allowed value and doesn't try to send fake value
							$multi_fields = array();
							$allowedValues = $jpaginate_config->filters->multifilter->fieldName;

							for ($j=0; $j<count($allowedValues); $j++)
							{
								list($v,$name) = explode(":", $allowedValues[$j]->field);
								$multi_fields[$j] = $v;
							}
					
							//if (in_array($values[$i], $allowedVal))	$allow = true;
							if (count($multi_fields) >= 0) $allow = true;
						}
						else {
							break;
						}
							
						$value = intval($multi_values[0]);
						
						if (isset($jpaginate_config->filters->multifilter->fieldName[$value]->compare))
							$compare = $jpaginate_config->filters->multifilter->fieldName[$value]->compare; 
						else 
							$compare = "like";
						
						if ($allow == true) 
						{
							switch ($compare)
							{
									case 'more':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." > '".mysql_real_escape_string($multi_values[1])."'";
										break;
									case 'less':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." < '".mysql_real_escape_string($multi_values[1])."'";
										break;
									case 'equal':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." = '".mysql_real_escape_string($multi_values[1])."'";
										break;
									case 'like':
									default: 
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." LIKE '%".mysql_real_escape_string($multi_values[1])."%'";
										break;
							}
							
						}
							
						if (isset($jpaginate_config->filters->multifilter->fieldName[$value]->sql))
						{
							$search_where = $jpaginate_config->filters->multifilter->fieldName[$value]->sql.' '.$search_where;
						}

				}
			}// end if $num
		}
	}
	
	return $search_where;
}


function jpaginate_getLimitSQL($current_page)
{
	
	
//	if(isset($_GET['numrows'])&&($_GET['numrows']==0)) {
		$sql_limit = " LIMIT ".($current_page - 1)*$_GET['max_results'].", ".$_GET['max_results'];
//	}else{
//		$line_page_start = ($current_page)*$_GET['max_results']-intval($_GET['numrows']);	
//		$getList .= "LIMIT ".$line_page_start.", ".intval($_GET['numrows']);
//	}
	

	return $sql_limit;

}


function jpaginate_getSortSQL()
{
	global $jpaginate_config;
	
	$res = "";

	if (isset($_GET['jp_sort']) && $_GET['jp_sort']!="") 
	{
		$jp_sort = intval($_GET['jp_sort']);
		$jp_sortd = isset($_GET['jp_sortd']) && !empty($_GET['jp_sortd']) ? mysql_real_escape_string($_GET['jp_sortd']) : "";
		
		if (isset($jpaginate_config->columns->column[$jp_sort]))
		{
			if (isset($jpaginate_config->columns->column[$jp_sort]['sort']) && !empty($jpaginate_config->columns->column[$jp_sort]['sort']))
				$res .= $jpaginate_config->columns->column[$jp_sort]['sort']." ".$jp_sortd;
		}
		if (!empty($res)) $res = " ORDER BY ".$res;
	}

	return $res;
}


function jpaginate_buildTableHeaders()
{
	global $jpaginate_config;

	$res = "";
	
	//$jp_sort = isset($_GET['jp_sort']) && !empty
	
	$num = count($jpaginate_config->columns->column);
	
	for($i = 0; $i < $num; $i++) {
		
		//$res .= jp_hook_add_column_header($i);
		
		if (!isset($jpaginate_config->columns->column[$i]['show']) || $jpaginate_config->columns->column[$i]['show'] == 1)
		{
		
			$hclass = isset($jpaginate_config->columns->column[$i]['hclass']) && !empty($jpaginate_config->columns->column[$i]['hclass']) ? ' class="'.addslashes($jpaginate_config->columns->column[$i]['hclass']).'" ' : "";
			$res .= "<th ".$hclass.">";
		
		
			if(isset($jpaginate_config->columns->column[$i]['sort']) && $jpaginate_config->columns->column[$i]['sort'] != "") { //ADD SORTING OPTIONS
                    
  	           if (isset($_GET['jp_sort']) && $_GET['jp_sort'] == $i  && $_GET['jp_sortd'] == "asc") { 
  	            	$res .= '<img src="'.JPAG_IMAGES.'/ic_up_sel.gif" alt="ASC" border="0" />';
   	           }
   	           else { 
   	           		$res .= '<img src="'.JPAG_IMAGES.'/ic_up.gif" alt="ASC" border="0" onClick="changeSortCriteria(\''.$i.'\', \'asc\')" />';
               }

			   if (isset($_GET['jp_sort']) && $_GET['jp_sort'] == $i  && $_GET['jp_sortd'] == "desc") { 
              		$res .= '&nbsp;<img src="'.JPAG_IMAGES.'/ic_down_sel.gif" alt="DESC" border="0" />&nbsp;';
               }
               else { 
              		$res .= '&nbsp;<img src="'.JPAG_IMAGES.'/ic_down.gif" alt="DESC" border="0" onClick="changeSortCriteria(\''.$i.'\', \'desc\')" />&nbsp;';
               }


         	}
		
		 	$res .= isset($jpaginate_config->columns->column[$i]['title']) ? htmlentities($jpaginate_config->columns->column[$i]['title']) : ""; 

			$res .= "</th>";
		}
	}
	return $res;
}

function jpaginate_buildTableColGroups()
{
	global $jpaginate_config;

	$colgroup = "<colgroup>";
		
	$num = count($jpaginate_config->columns->column);
	
	for($i = 0; $i < $num; $i++) {
			
		if (!isset($jpaginate_config->columns->column[$i]['show']) || $jpaginate_config->columns->column[$i]['show'] == 1)
		{
			if ($jpaginate_config->columns->column[$i]['colWidth'])
				$colgroup .= '<col style="width:'.addslashes($jpaginate_config->columns->column[$i]['colWidth']).'" />';
			else
				$colgroup .= "<col/>";
		}
	}
	$colgroup .= "</colgroup>";
	
	return $colgroup;
}


function jpaginate_buildTableRows($data)
{
	global $jpaginate_config;

	$content_array = array();
	
	//$res = "<tbody>";
	$n = 0;
	while ($row = mysql_fetch_assoc($data))
	{
		$n++;
		$content_array[$n] = array();
		
		//$res .= '<tr id="'.$row[strval($jpaginate_config->tableID)].'">';
		$content_array[$n]['tr'] = '<tr id="'.$row[strval($jpaginate_config->tableID)].'">';
		
		$num = count($jpaginate_config->columns->column);
		for($i = 0; $i < $num; $i++) {

			$id = "";
			$content_array[$n]['td'][$i] = array();

			$class = isset($jpaginate_config->columns->column[$i]['class']) && !empty($jpaginate_config->columns->column[$i]['class']) ? ' class="'.$jpaginate_config->columns->column[$i]['class'].'" ' : "";
			$style = isset($jpaginate_config->columns->column[$i]['style']) && !empty($jpaginate_config->columns->column[$i]['style']) ? ' style="'.$jpaginate_config->columns->column[$i]['style'].'" ' : "";

			$parts = count($jpaginate_config->columns->column[$i]->content);
			/*if ($parts == 1 && $n == 1)
			{
				$tmp_count = 0;
				$p = strval($jpaginate_config->columns->column[$i]->content[0]);
				$tmp_str = str_replace("{*", "",$p, $tmp_count);
				if ($tmp_count == 1)
				{
					$start = strpos($p,"{*");
					$end = strpos($p,"*}");
					$field = substr($p, $start+2, $end - $start - 2);
					if (!isset($row[$field]))
					{
						$jpaginate_config->columns->column[$i]['show'] = 0;
						//var_dump($jpaginate_config->columns->column[$i]['show']);
					}
				}
			}*/
			
			
			if (!isset($jpaginate_config->columns->column[$i]['show']) || $jpaginate_config->columns->column[$i]['show'] == 1)
			{
			
				//$res .= '<td '.$id.$class.' '.$style.'>';
				$content_array[$n]['td'][$i]['style'] = '<td '.$id.$class.' '.$style.'>';
				
				$content_array[$n]['td'][$i]['content'] = "";
			
			
				for($j = 0; $j < $parts; $j++) 
				{
					//$part = null;
					$part = strval($jpaginate_config->columns->column[$i]->content[$j]);
					$original_content = $part;
								

					// replace db_fields with real values
					$start = strpos($part,"{*");
					$end = strpos($part,"*}");
					while ($start !== false  && $end !== false) 
					{
						$field = substr($part, $start+2, $end - $start - 2);
						$val = isset($row[$field]) ? $row[$field] : "";
						$part = str_replace("{*".$field."*}", $val, $part);
						$start = strpos($part,"{*");
						$end = strpos($part,"*}");
					}
				
			
					// assign plugin if it exists
				
					if (isset($jpaginate_config->columns->column[$i]->content[$j]['plugin']) && !empty($jpaginate_config->columns->column[$i]->content[$j]['plugin']))
					{
					
						$jpaginate_config->columns->column[$i]->content[$j] = $part;
						$part = jpaginate_getPluginContent($jpaginate_config->columns->column[$i]->content[$j]);
						$jpaginate_config->columns->column[$i]->content[$j] = $original_content;
					}
				
			
					// check and run format field function
					$formatFunct = isset($jpaginate_config->columns->column[$i]->content[$j]['applyFunction']) ? $jpaginate_config->columns->column[$i]->content[$j]['applyFunction'] : "";
					if (!empty($formatFunct))  {
					
					
						$functions = explode(";", $formatFunct);
					
						foreach ($functions as $func) {
							$part = jpaginate_format($part, trim($func));
						}

					}
				
					// check if content is a link  and build it in case ...
					if (isset($jpaginate_config->columns->column[$i]->content[$j]['linkUrl']) && !empty($jpaginate_config->columns->column[$i]->content[$j]['linkUrl']) )
					{
				
						$href = $jpaginate_config->columns->column[$i]->content[$j]['linkUrl'];
						$start = strpos($href,"{*");
						$end = strpos($href,"*}");
						while ($start !== false  && $end !== false) 
						{
							$field = substr($href, $start+2, $end - $start - 2);
							$val = isset($row[$field]) ? $row[$field] : "";
							$href = str_replace("{*".$field."*}", $val, $href);
							$start = strpos($href,"{*");
							$end = strpos($href,"*}");
						}
					
						$linkType = isset($jpaginate_config->columns->column[$i]->content[$j]['linkType']) ? $jpaginate_config->columns->column[$i]->content[$j]['linkType'] : "";
						$attributeOptions = isset($jpaginate_config->columns->column[$i]->content[$j]['linkAttributes']) ? $jpaginate_config->columns->column[$i]->content[$j]['linkAttributes'] : "";
					
						if (!empty($attributeOptions)) {
							// replace db_fields with real values
							$start = strpos($attributeOptions,"{*");
							$end = strpos($attributeOptions,"*}");
							while ($start !== false  && $end !== false) 
							{
								$field = substr($attributeOptions, $start+2, $end - $start - 2);
								$val = isset($row[$field]) ? $row[$field] : "";
								$attributeOptions = str_replace("{*".$field."*}", $val, $attributeOptions);
								$start = strpos($attributeOptions,"{*");
								$end = strpos($attributeOptions,"*}");
							}
						}
					
						switch ($linkType) {
							case "blank" : 
								$attr = ' target="_blank" '.$attributeOptions;
								break;
							case "popup" : 
								$attr = "";
					 			$href = "javascript: void window.open('".$href."', 'jp_popup_".$i."_".$row[strval($jpaginate_config->tableID)]."', '".$attributeOptions."')";
								break;
							case "custom" :     
								$attr = $attributeOptions;
								break;
							default :
								$attr = $attributeOptions;
						}
						$part = '<a href="'.$href.'" '.$attr.'>'.$part.'</a>';
					}
				
					//$res .= $part;
					$content_array[$n]['td'][$i]['content'] .= $part;
				}


				//$res .= '</td>';
			}// end check if show == 1
		}
		
		//$res .= "</tr>";
	}
	//$res .= "</tbody>";
	//return $res;
	
	return jpaginate_buildHtmlRows($content_array);
	
}



function jpaginate_buildHtmlRows($data)
{
	global $jpaginate_config;
	
	$rowcount = count($data);
	$colcount = count($jpaginate_config->columns->column);
	
	// lets find empty columns and mark them as hidden
	for ($i=0;$i<$colcount;$i++)
	{
		$empty = true;
		for ($j=1;$j<$rowcount+1;$j++)
		{
			$v = trim($data[$j]['td'][$i]['content']);
			if (!empty($v))
			{
				$empty = false;
				break;
			}
		}
		if ($empty)
			$jpaginate_config->columns->column[$i]['show'] = 0;
	}
//var_dump($data);
	// lets build html
	$res = "<tbody>";

	for ($i=1;$i<$rowcount+1;$i++)
	{
		$res .= $data[$i]['tr'];

		for ($j=0;$j<$colcount;$j++)
		{
			if (!isset($jpaginate_config->columns->column[$j]['show']) || $jpaginate_config->columns->column[$j]['show'] == 1)
				$res .=  $data[$i]['td'][$j]['style'].$data[$i]['td'][$j]['content']."</td>";

		}
		
		$res .= "</tr>";
	}
	
	$res .= "</tbody>";
	
	return $res;
}



function jpaginate_buildPageNumbering($current_page, $total_pages)
{
	global $jpaginate_config; 
	
	//$res = "<div class=\"jp_pageNumbering\">";
	$res = "";
	$last_center_i = 0;
	$last_right_i = 0;
	$last_left_i = 0;

	if ($total_pages > 1) { 

		// previous and first links
		if ($current_page > 1) {
			if (intval($jpaginate_config->pagination->showFirstLink) == 1)	$res .= '<span class="jp_pagination"><a onclick="changePage(\'1\')">first</a></span>'; 
			if (intval($jpaginate_config->pagination->showPrevLink) == 1)	$res .= '<span class="jp_pagination"><a onclick="changePage(\''.($current_page-1).'\')">prev</a></span>'; 
		}

	
		// left group links
		for ($i = 1; $i < ($jpaginate_config->pagination->leftGroup+1) && $i < ($current_page-$jpaginate_config->pagination->centerGroup); $i++)
		{
			if ($i < $current_page)	$res .= '<span class="jp_pagination"><a onclick="changePage(\''.$i.'\')">'.number_format($i).'</a></span>'; 
			elseif ($i == $current_page) $res .= '<span class="jp_pagination_selected">'.number_format($i).'</span>';
			else break;
			$last_left_i = $i;
		}
		if ($jpaginate_config->pagination->leftGroup + 1 <$current_page-$jpaginate_config->pagination->centerGroup)	
			$res .= "...";
	
	
		// center group links
		for ($i = ($current_page - $jpaginate_config->pagination->centerGroup); $i<($current_page+$jpaginate_config->pagination->centerGroup+1); $i++)
		{
			if ($i>0 && $i<($total_pages+1))
			{

				if ($i == $current_page)
				{
					$res .= '<span class="jp_pagination_selected">'.number_format($i).'</span>'; 
				}
				else
				{
					$res .= '<span class="jp_pagination"><a onclick="changePage(\''.$i.'\')">'.number_format($i).'</a></span>';
				}
				$last_center_i = $i;
			}
		
		}
	
	
		// right group links
		$temp = "";
		for ($i = $total_pages; ($i > $total_pages - $jpaginate_config->pagination->rightGroup && $i > $current_page+$jpaginate_config->pagination->centerGroup); $i--)
		{
			if ($i > $current_page)
				$temp = '<span class="jp_pagination"><a onclick="changePage(\''.$i.'\')">'.number_format($i).'</a></span>'.$temp; 
			elseif ($i == $current_page) 
				$temp = '<span class="jp_pagination_selected">'.number_format($i).'</span>'.$temp;
			else break;
			$last_right_i = $i;
		}
		if ($last_center_i < $last_right_i-1) $res .= "...";
		$res .= $temp;
		unset($temp);
	
	
		// last and next links
		if ($current_page < $total_pages) {
			if (intval($jpaginate_config->pagination->showNextLink) == 1)
				$res .= '<span class="jp_pagination"><a onclick="changePage(\''.($current_page+1).'\')">next</a></span>'; 
			if (intval($jpaginate_config->pagination->showLastLink) == 1)
				$res .= '<span class="jp_pagination"><a onclick="changePage(\''.$total_pages.'\')">last</a></span>'; 
		}
	}
	
	
	//$res .= "</div>";
	return $res;
}


function jpaginate_format($val, $func)
{
	global $jpaginate_config;
	
	if (!empty($func))
	{
		if (!function_exists($func) && is_file(JPAG_FORMAT_FUNCTIONS."function.".$func.".php")) 
		{
			include_once(JPAG_FORMAT_FUNCTIONS."function.".$func.".php");
		}
 		
 		if (function_exists($func))
		{
			$val = call_user_func($func, $val);
		}
	}
	
	return $val;
}

function jpaginate_updateData()
{
	if (isset($_GET['plugin']) && !empty($_GET['plugin']) ) 
		return jpaginate_plugin_updateData(trim($_GET['plugin']));
	else 
		return 0;
}
?>
