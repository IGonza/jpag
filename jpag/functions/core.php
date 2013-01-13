<?php
function jpaginate_include_head()
{
	global $jpaginate_config;
	
	if ( (!isset($jpaginate_config->loadJQUERY) || $jpaginate_config->loadJQUERY == "true") && JPAG_JQUERY != "")
			echo "<script type=\"text/javascript\" src=\"".JPAG_JQUERY."\"></script>\n";

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
		$sort_d = trim(strtolower($sort['1']));
	}
	echo "<input type=\"hidden\" id=\"jp_sort\" value=\"".$sort_column."\">";
	echo "<input type=\"hidden\" id=\"jp_sortd\" value=\"".$sort_d."\">";
	echo "<input type=\"hidden\" id=\"jp_gets\" value=\"".$_SERVER['QUERY_STRING']."\">";
}


function jpaginate_build_filters()
{
	global $jpaginate_config, $jp_dbdata_conn;
	$showfilter ="";
	if (isset($jpaginate_config->filters) && (!empty($jpaginate_config->filters->filter) || !empty($jpaginate_config->filters->multifilter)) )
	{
		// example: <filters style="display:hidden" format="table/default" class="alertBoxBlue">
		$style = (!empty($jpaginate_config->filters['style'])?' style="'.trim($jpaginate_config->filters['style']).'"':"");
		$class = (!empty($jpaginate_config->filters['class'])?' class="'.trim($jpaginate_config->filters['class']).'"':"");
		$Ftable = (isset($jpaginate_config->filters['format']) && $jpaginate_config->filters['format']=='table')?'1':false;
		$labelstyle = (!empty($jpaginate_config->filters['labelstyle'])?$jpaginate_config->filters['labelstyle']:'width:100px;');
		
		$showfilter .= "<div ".$style.$class.">";
		
		if($Ftable) $showfilter .= "<table style=\"width:100%;\">";
		
		
		$num =  count($jpaginate_config->filters->filter); // if num is greater than 4, auto format to table
		for($i = 0; $i < $num; $i++) {
		
			$grouptop = (isset($jpaginate_config->filters->filter[$i]['grouptop']))?'1':0; // top group must have this
			$group = (isset($jpaginate_config->filters->filter[$i]['group']))?'1':0; // group in middle must have this
			$groupstop = (isset($jpaginate_config->filters->filter[$i]['groupstop']))?'1':0; // must end grouping with this
			$style = (!empty($jpaginate_config->filters->filter[$i]->style))?' style="'.trim($jpaginate_config->filters->filter[$i]->style).'"':"";
			$filtervisible = (isset($jpaginate_config->filters->filter[$i]->filtervisible) && $jpaginate_config->filters->filter[$i]->filtervisible=='no')?' style="display:none;"':'';
			$title = (isset($jpaginate_config->filters->filter[$i]->title))?htmlentities($jpaginate_config->filters->filter[$i]->title):"";
			
			if($Ftable && empty($group) && empty($groupstop)) $showfilter .= "<tr $filtervisible><td style='".$labelstyle.";'>";	////////////////
						$showfilter .= "<span $filtervisible class=\"jp_filter_title\">".$title."</span>";
			if($Ftable && empty($group) && empty($groupstop)) $showfilter .= "</td><td $filtervisible>";	////////////////
			
			
			// make the actual filter type
			switch ($jpaginate_config->filters->filter[$i]->type) {
				case "searchbox" :
					//<filter>
						//<type>searchbox</type>
						//<style>width:200px;</style>
						//<title>Search for: </title>
						//<defaultValue></defaultValue>
						//<placeholder></placeholder>
						//<fieldName>notesubject</fieldName>
						//<autosubmit>0</autosubmit>
						//<focused>1</focused>
					//</filter>
					$id = "jp_string_search_".$i;
					$defVal = (isset($jpaginate_config->filters->filter[$i]->defaultValue)) ? $jpaginate_config->filters->filter[$i]->defaultValue : "";
					$placeholder = (isset($jpaginate_config->filters->filter[$i]->placeholder)) ? 'placeholder="'.$jpaginate_config->filters->filter[$i]->placeholder.'"' : "";
					$action = (isset($jpaginate_config->filters->filter[$i]->autosubmit) && !empty($jpaginate_config->filters->filter[$i]->autosubmit))?$action = "onkeyup=\"changeSearchCriteria()\"":"";
					$cl = (isset($jpaginate_config->filters->filter[$i]->focused))?$cl = " focused":"";
					
					$showfilter .= "<input type=\"text\" class=\"jp_filter".$cl."\" ".$action." id=\"".$id."\" value=\"".htmlentities($defVal)."\" $style $placeholder />&nbsp;";
						
					break;
				
				case "menu" :
						// <filter>
						//	<type>menu</type>
						//	<fieldName>c.customeronoff</fieldName>
						//	<values>1:Open Accounts|0:Closed Accounts</values>
						//	<defaultValue>1</defaultValue>
						//	<autosubmit>0</autosubmit>
						// </filter>
			
						//if($Ftable) $showfilter .= "<tr $filtervisible><td style='width:100px;'>";	////////////////
									//$showfilter .= "<span $filtervisible class=\"jp_filter_title\">".$title."</span>";
						//if($Ftable) $showfilter .= "</td><td $filtervisible>";	////////////////
					    $id = "jp_menu_filter_".$i;
						$defVal = (isset($jpaginate_config->filters->filter[$i]->defaultValue)) ? $jpaginate_config->filters->filter[$i]->defaultValue : "";
						// for multi-select
						$defVal = explode(",", $defVal);
						if (isset($jpaginate_config->filters->filter[$i]->autosubmit) && !empty($jpaginate_config->filters->filter[$i]->autosubmit)){ $action = "onChange=\"changeSearchCriteria()\""; }else{ $action = ""; }
						if (isset($jpaginate_config->filters->filter[$i]->focused) && !empty($jpaginate_config->filters->filter[$i]->focused)){ $cl = " focused"; }else{ $cl = ""; }
						if (isset($jpaginate_config->filters->filter[$i]->type['multiple']) && intval($jpaginate_config->filters->filter[$i]->type['multiple'])>0){ $mp = " multiple='multiple' size='".intval($jpaginate_config->filters->filter[$i]->type['multiple'])."'"; }else{ $mp = ""; } 
						
						$showfilter .= "<select class=\"jp_filter".$cl."\" id=\"".$id."\" ".$action." ".$style." ".$mp.">";
						
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						// all Options select text
						$allOptionsValue = isset($jpaginate_config->filters->filter[$i]->allOptionsValue)?$jpaginate_config->filters->filter[$i]->allOptionsValue:'';
						$allOptionsText = isset($jpaginate_config->filters->filter[$i]->allOptionsText)?$jpaginate_config->filters->filter[$i]->allOptionsText:'--Select All--';
						if (isset($jpaginate_config->filters->filter[$i]->allOptions) && $jpaginate_config->filters->filter[$i]->allOptions == 0)
						{
							$showfilter .= '';
						}else{
							$showfilter .= '<option value="'.htmlentities($allOptionsValue).'">'.htmlentities($allOptionsText).'</option>';
						}
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							
						if (isset($jpaginate_config->filters->filter[$i]->values))
						{
							$values = explode("|", $jpaginate_config->filters->filter[$i]->values);
							foreach ($values as $val)
							{
								list($v,$name) = explode(":", $val);
								$selected = "";
								if (in_array($v,$defVal)) $selected = " selected=\"selected\" ";
								$showfilter .= "<option value=\"".$v."\" ".$selected.">".htmlentities($name)."</option>";
							}
						}
						
						
						if (isset($jpaginate_config->filters->filter[$i]->mysqlValues))
						{
							$res = mysql_query($jpaginate_config->filters->filter[$i]->mysqlValues, $jp_dbdata_conn) or die(mysql_error());
							while ($row = mysql_fetch_assoc($res)) 
							{
								$selected = "";
								if (in_array($row['val'],$defVal)) $selected = " selected=\"selected\" ";
								$showfilter .= "<option value=\"".$row['val']."\" ".$selected.">".htmlentities($row['name'])."</option>";
							}
						}
						
						$showfilter .= "</select>&nbsp;";
						//if($Ftable) $showfilter .= "</td></tr>";	////////////////
						
					default:
						break;
				}
				
				
			if($Ftable && empty($grouptop) && empty($group)) $showfilter .= "</td></tr>";	////////////////
			
		}
		
		
		// multifilters
		$num =  count($jpaginate_config->filters->multifilter);
		if ($num)
		{
			//$id = "jp_menu_multifilter_0";
			$defaultID = (isset($jpaginate_config->filters->multifilter->defaultID)) ? $jpaginate_config->filters->multifilter->defaultID : "jp_menu_multifilter_select";
			if($Ftable) $showfilter .= "<tr><td style='width:100px;'>";	////////////////
			$showfilter .= (isset($jpaginate_config->filters->multifilter->title)) ? $jpaginate_config->filters->multifilter->title : "";
			if($Ftable) $showfilter .= "</td><td>";	////////////////
			$showfilter .= "<select class=\"jp_multifilter\" id=\"".$defaultID."\" >";
			$showfilter .= "<option value=\"\" selected='selected'>------------</option>";
			if (count($jpaginate_config->filters->multifilter->fieldName))
			{
				$values = $jpaginate_config->filters->multifilter->fieldName;
				$ii = 0;
				foreach ($values as $val)
				{
						list($v,$name) = explode(":", $val->field); // this is what I want to change!!!  no parsing.
						$compare = (isset($jpaginate_config->filters->multifilter->fieldName[$ii]->compare)) ? $jpaginate_config->filters->multifilter->fieldName[$ii]->compare : 'like';
						$placeholder = (isset($jpaginate_config->filters->multifilter->fieldName[$ii]->placeholder)) ? 'placeholder="'.$jpaginate_config->filters->multifilter->fieldName[$ii]->placeholder.'"' : '';
						$showfilter .= "<option value=\"".$ii."\" compare='$compare' $placeholder>".htmlentities($name)."</option>";
						$ii++;
				}
				
			}
			$showfilter .= "</select>&nbsp;";
			$showfilter .= "<select disabled='disabled' style='display:none;' class=\"jp_multifilter\" id=\"".$defaultID."_2\" >";
				$showfilter .= '<option value="equal">EQUAL To</option>';
				$showfilter .= '<option value="like_left">LIKE %....</option>';
				$showfilter .= '<option value="like_right">LIKE ....%</option>';
				$showfilter .= '<option value="like">LIKE %....%</option>';
			$showfilter .= "</select>&nbsp;";
			$showfilter .= "<input disabled='disabled' type=\"text\" id=\"".$defaultID."_3\" class=\"jp_multifilter\" style=\"width:250px;display:none;\" />";
			if($Ftable) $showfilter .= "</td></tr>";	////////////////
		}
		// end multifilters
		
		
		
		// search button
		if (isset($jpaginate_config->filters->button)) {
			if($Ftable) $showfilter .= "<tr><td style='width:100px;'>";	////////////////
			$bID = (!empty($jpaginate_config->filters->button['id'])) ? 'id="'.$jpaginate_config->filters->button['id'].'"': "";
			$val = (!empty($jpaginate_config->filters->button)) ? strval($jpaginate_config->filters->button) : "Search";
			$class = (!empty($jpaginate_config->filters->button['class'])) ? strval($jpaginate_config->filters->button['class']) : "inplace_save";
			$showfilter .= "<input type=\"button\" ".$bID." value=\"".$val."\" onClick=\"changeSearchCriteria()\" class=\"".$class."\" />";
			if($Ftable) $showfilter .= "</td><td></td></tr>";	////////////////
		}
		
		if($Ftable) $showfilter .= "</table>";
		$showfilter .= "</div>";
		if(!empty($showfilter)){ echo $showfilter; }
	}
}


function jpaginate_build_maxResultBox()
{
	global $jpaginate_config;
	
	$showing = isset($jpaginate_config->text->showing) ? ($jpaginate_config->text->showing) : "";
	$of = isset($jpaginate_config->text->of) ? ($jpaginate_config->text->of) : "";
	$total_results = isset($jpaginate_config->text->total_results) ? ($jpaginate_config->text->total_results) : "";
	
	echo "<span class='jp_showing_text'>".$showing."</span><span id='jp_max_results_span'><select name=\"jp_max_results\" id=\"jp_max_results\" onchange=\"changeMaxResults(this.value)\">";
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
	if (!empty($res)) $res = "<div id=\"jpaginate_buttons\">".$res."</div>";
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


//debug instance
if (defined('JP_DEBUG') && JP_DEBUG == TRUE){ 
	echo '<div id="jp_debug_div" style="margin:3px 0 3px 0;border:1px solid red;background-color:#FFEAC0;font-size:10px;">';
	echo '<table><tr><td><a href="javascript:void(0);" onclick="openjpdebug()" id="open_jp_debug_table">define("JP_DEBUG", "1");</a></td></tr></table>';
	echo '<table id="jp_debug_table" style="display:none;">';
	echo '<tr><td><hr style="border: 1px dashed #000;"></td></tr>';
	/////////////////////////////////////////////////////////////////////////////////////////
	$time_start = microtime(true); 
}


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


//debug instance
if (defined('JP_DEBUG') && JP_DEBUG == TRUE){ 
    echo "<tr><td><b>Count sql:</b></td></tr><tr><td>".$count_sql."</td></tr>";
	echo '<tr><td><hr style="border: 1px dashed #000;"></td></tr>';
	/////////////////////////////////////////////////////////////////////////////////////////
}
	
	//counting query
	$mysql_res = mysql_query($count_sql, $jp_dbdata_conn) or die(mysql_error());
	$r = mysql_fetch_row($mysql_res);
	$totalRows = $r[0];
	$sql .= jpaginate_getSortSQL();
	$sql .= jpaginate_getLimitSQL($current_page);
	
	
	
	
//debug instance
if (defined('JP_DEBUG') && JP_DEBUG == TRUE){ 
    echo "<tr><td><b>Main sql:</b></td></tr><tr><td>".$sql."</td></tr>";
	echo '<tr><td><hr style="border: 1px dashed #000;"></td></tr>';
	/////////////////////////////////////////////////////////////////////////////////////////
}

	$result = mysql_query($sql, $jp_dbdata_conn)  or die(mysql_error());


//debug instance
if (defined('JP_DEBUG') && JP_DEBUG == TRUE){
	$time_end = microtime(true);
	$time = $time_end - $time_start;
    echo "<tr><td><b>Total queries time:</b></td></tr><tr><td>".$time."</td></tr>";
	/////////////////////////////////////////////////////////////////////////////////////////
	echo "</table>";
	echo "</div>";
}

	$total_pages = ceil($totalRows / $_GET['max_results']);
	
	$display = "";
	$listRows    = jpaginate_buildTableRows($result);
	$listHeaders = jpaginate_buildTableHeaders();
	
	$display_paginate = "";
	if ($totalRows)
	{
		$display_paginate = jpaginate_buildPageNumbering($current_page, $total_pages);
		$tableID = isset($jpaginate_config->tableID) ? ' id="'.$jpaginate_config->tableID.'"' : ' id="'.rand().'"';
		$tableClass = isset($jpaginate_config->tableClass) ? ' class="'.$jpaginate_config->tableClass.'"' : ' class="jp_data"';
		$tableStyle = isset($jpaginate_config->tableStyle) ? ' style="'.$jpaginate_config->tableStyle.'"' : '';

		$display = '<form name="pagination_form">';
	    $display .= '<table'.$tableID.$tableClass.$tableStyle.'>';
			$display .= '<thead>';
				$display .= '<tr>';
					$display .= $listHeaders;
				$display .= "</tr>";
			$display .= "</thead>";
    	$display .= $listRows;
	    $display .= "</table>";
    	$display .= "</form>";
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
	
	$jpaginate_config = loadConfig(CONFIG.JPAGINATE_CONFIG);
	require_once(JPAG_FUNCTIONS."plugins.php");
	
	ob_start();
	require_once(JPAG_FUNCTIONS."jpaginate.js.php");
	mysql_query("INSERT INTO js_files(id, content) VALUES ('".mysql_real_escape_string($f_id)."', '".mysql_real_escape_string(ob_get_contents())."') ", $jp_dbmain_conn);
	ob_end_clean();
}



function jpaginate_getSearchWhereSQL($search_val, $multisearch_val)
{
	global $jpaginate_config;
	
	$search_where = " ";
	//trigger_error("real: ?. ".$multisearch_val);
	
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
									case 'more_equal':
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." >= '".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."'";
										break;
									case 'less':
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." < '".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."'";
										break;
									case 'less_equal':
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." <= '".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."'";
										break;
									case 'equal':
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." = '".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."'";
										break;
									case 'like_right':
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." LIKE '".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."%'";
										break;
									case 'like_left':
										$search_where .= $art.mysql_real_escape_string($jpaginate_config->filters->filter[$i]->fieldName[$j])." LIKE '%".mysql_real_escape_string(jpaginate_format($values[$i],$applyFunction))."'";
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
			if ($num)
			{
			
				if (isset($multi_values[0]) && $multi_values[0]!="" && isset($multi_values[2]) && $multi_values[2]!="") 
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
					
							if (count($multi_fields) >= 0) $allow = true;
						}
						else {
							break;
						}
							
						$value = intval($multi_values[0]);
						//if (isset($jpaginate_config->filters->multifilter->fieldName[$value]->compare)){
							//$compare = $jpaginate_config->filters->multifilter->fieldName[$value]->compare; 
							//trigger_error('not shure:'.$compare);
						if(!empty($multi_values[1])){
							$compare = $multi_values[1];
							//trigger_error('here:'.$compare);
						}else{ 
							$compare = "like";
							//trigger_error('what?:'.$compare);
						}	
						
						if ($allow == true) 
						{
							switch ($compare)
							{
									case 'more':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." > '".mysql_real_escape_string($multi_values[2])."'";
										break;
									case 'more_equal':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." >= '".mysql_real_escape_string($multi_values[2])."'";
										break;
									case 'less':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." < '".mysql_real_escape_string($multi_values[2])."'";
										break;
									case 'less_equal':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." <= '".mysql_real_escape_string($multi_values[2])."'";
										break;
									case 'like':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." LIKE '%".mysql_real_escape_string($multi_values[2])."%'";
										break;
									case 'like_right':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." LIKE '".mysql_real_escape_string($multi_values[2])."%'";
										break;
									case 'like_left':
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." LIKE '%".mysql_real_escape_string($multi_values[2])."'";
										break;
									case 'equal':
									default: 
										$search_where .= " AND ".mysql_real_escape_string($multi_fields[$value])." = '".mysql_real_escape_string($multi_values[2])."'";
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
	if(!empty($jpaginate_config->tableheader) && $jpaginate_config->tableheader == 'hide'){ return $res; }
	$num = count($jpaginate_config->columns->column);
	
	for($i = 0; $i < $num; $i++) {
		
		$title = ($jpaginate_config->columns->column[$i]['title'])?htmlentities($jpaginate_config->columns->column[$i]['title']) : '';
		$class = ($jpaginate_config->columns->column[$i]['class'])?' class="'.addslashes($jpaginate_config->columns->column[$i]['class']).'"':'';
		$style = ($jpaginate_config->columns->column[$i]['style'])?' style="'.addslashes($jpaginate_config->columns->column[$i]['style']).'"':'';
		$colspan = ($jpaginate_config->columns->column[$i]['colspan'])?' colspan="'.addslashes($jpaginate_config->columns->column[$i]['colspan']).'"':'';
		
		$res .= "<th".$class.$style.$colspan.">";
		// column header sorters
		if(isset($jpaginate_config->columns->column[$i]['sort']) && $jpaginate_config->columns->column[$i]['sort'] != "") { //ADD SORTING OPTIONS
			$res .= '<span class="jp_sort_imgs">';
		   if (isset($_GET['jp_sort']) && $_GET['jp_sort'] == $i && $_GET['jp_sortd'] == "asc") { 
				$res .= '<img src="'.JPAG_IMAGES.'ic_up_sel.gif" alt="ASC" border="0" />';
		   } else { 
				$res .= '<img src="'.JPAG_IMAGES.'ic_up.gif" alt="ASC" border="0" onClick="changeSortCriteria(\''.$i.'\', \'asc\')" />';
		   }

		   if (isset($_GET['jp_sort']) && $_GET['jp_sort'] == $i && $_GET['jp_sortd'] == "desc") { 
				$res .= '&nbsp;<img src="'.JPAG_IMAGES.'ic_down_sel.gif" alt="DESC" border="0" />&nbsp;';
		   } else { 
				$res .= '&nbsp;<img src="'.JPAG_IMAGES.'ic_down.gif" alt="DESC" border="0" onClick="changeSortCriteria(\''.$i.'\', \'desc\')" />&nbsp;';
		   }
		
		    $res .= "<br>"; 
			$res .= '</span>';
		}
		// column header title
		$res .= $title; 
		$res .= "</th>";
	}
	return $res;
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
		
		$tablerowID = !empty($jpaginate_config->tablerowID) ? $row[strval($jpaginate_config->tablerowID)] : '';
		$tablerowID_prefix = !empty($jpaginate_config->tablerowID['prefix']) ? $jpaginate_config->tablerowID['prefix'] : ''; // nice for future usage, if necessary
		$tablerowClass = !empty($jpaginate_config->tablerowClass) ? ' class="'.$jpaginate_config->tablerowClass.'"' : '';
		
//		<tablerowRandomAttributes>
//			<RandomAttribute name="queuestatus">queuestatus</RandomAttribute>
//			<RandomAttribute name="checkdate">checkdate</RandomAttribute>
//			<RandomAttribute name="flagged">flagged</RandomAttribute>
//		</tablerowRandomAttributes>
		
		// necessary for advanced plugins
		$allrandomattributes='';
		$randomAttrsNum = count($jpaginate_config->tablerowRandomAttributes->RandomAttribute);
		for($ra = 0; $ra < $randomAttrsNum; $ra++){
			$nameof = !empty($jpaginate_config->tablerowRandomAttributes->RandomAttribute[$ra]['name'])?$jpaginate_config->tablerowRandomAttributes->RandomAttribute[$ra]['name']:'rand'.$ra;
			$dbcolval = !empty($jpaginate_config->tablerowRandomAttributes->RandomAttribute[$ra]) ? $row[strval($jpaginate_config->tablerowRandomAttributes->RandomAttribute[$ra])] : 'empty';
			$allrandomattributes .= $nameof.'="'.$dbcolval.'"';
		}
		
		
		$content_array[$n]['tr'] = '<tr id="'.$tablerowID_prefix.'_'.$tablerowID.'"'.$tablerowClass.$allrandomattributes.'>';
		
		$num = count($jpaginate_config->columns->column);
		for($i = 0; $i < $num; $i++) {

			$content_array[$n]['td'][$i] = array();

			$class = !empty($jpaginate_config->columns->column[$i]->content['class']) ? ' class="'.$jpaginate_config->columns->column[$i]->content['class'].'"' : '';
			$style = !empty($jpaginate_config->columns->column[$i]->content['style']) ? ' style="'.$jpaginate_config->columns->column[$i]->content['style'].'"' : '';

			$content_array[$n]['td'][$i]['style'] = '<td '.$class.$style.'>';
			$content_array[$n]['td'][$i]['content'] = "";
		
			$parts = count($jpaginate_config->columns->column[$i]->content);
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
							$href = "javascript: void window.open('".$href."', 'jp_popup_".$i."_".$row[strval($jpaginate_config->tablerowID)]."', '".$attributeOptions."')";
							break;
						case "custom" :     
							$attr = $attributeOptions;
							break;
						default :
							$attr = $attributeOptions;
					}
					$part = '<a href="'.$href.'" '.$attr.'>'.$part.'</a>';
				}
			
				$content_array[$n]['td'][$i]['content'] .= $part;
			}

		}
		
	}
	return jpaginate_buildHtmlRows($content_array);
}



function jpaginate_buildHtmlRows($data)
{
	global $jpaginate_config;
	
	$rowcount = count($data);
	$colcount = count($jpaginate_config->columns->column);
	
	// lets find empty columns and mark them as hidden
	/*
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
	*/
//var_dump($data);

$td=0;
	// lets build html
	$res = "<tbody>";
	for ($i=1;$i<$rowcount+1;$i++)
	{
		
		if(!empty($tdcount) && $td < $tdcount){
		
		}else{
			$td=0;
			$res .= $data[$i]['tr'];
		}
		
		for ($j=0;$j<$colcount;$j++)
		{
				$td++;
				if(!empty($jpaginate_config->columns->column[$j]['repeathorizontal']))
				{
						// add </td> here
						$tdcount = $jpaginate_config->columns->column[$j]['repeathorizontal']; 
						//trigger_error($td);
				}
			
			
			
			$res .=  $data[$i]['td'][$j]['style'].$data[$i]['td'][$j]['content']."</td>";
		
			
		
		}
		
		if(!empty($tdcount) && $td < $tdcount) continue;
		
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
		
			$first = isset($jpaginate_config->text->first) && !empty($jpaginate_config->text->first) ? $jpaginate_config->text->first : 'first';
			$prev = isset($jpaginate_config->text->prev) && !empty($jpaginate_config->text->prev) ? $jpaginate_config->text->prev : 'prev';
			
			if (intval($jpaginate_config->pagination->showFirstLink) == 1)	$res .= '<span class="jp_pagination"><a onclick="changePage(\'1\')">'.$first.'</a></span>'; 
			if (intval($jpaginate_config->pagination->showPrevLink) == 1)	$res .= '<span class="jp_pagination"><a onclick="changePage(\''.($current_page-1).'\')">'.$prev.'</a></span>'; 
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
		
			$next = isset($jpaginate_config->text->next) && !empty($jpaginate_config->text->next) ? $jpaginate_config->text->next : 'next';
			$last = isset($jpaginate_config->text->last) && !empty($jpaginate_config->text->last) ? $jpaginate_config->text->last : 'last';
		
			if (intval($jpaginate_config->pagination->showNextLink) == 1)
				$res .= '<span class="jp_pagination"><a onclick="changePage(\''.($current_page+1).'\')">'.$next.'</a></span>'; 
			if (intval($jpaginate_config->pagination->showLastLink) == 1)
				$res .= '<span class="jp_pagination"><a onclick="changePage(\''.$total_pages.'\')">'.$last.'</a></span>'; 
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
	if (!empty($_GET['plugin']) ) 
		return jpaginate_plugin_updateData(trim($_GET['plugin']));
	else 
		return 0;
}
?>
