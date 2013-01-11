<?php

function loadConfig($conf)
{
	global $plugin_conf, $jpaginate_replacements;
	
	if (file_exists($conf) || defined("JP_CONFIG_CONTENT"))
	{

		if (!defined("JP_CONFIG_CONTENT"))
		{
			ob_start();
			include($conf);
			$xml = ob_get_contents();
			ob_end_clean();
			define("JP_CONFIG_CONTENT", $xml);
		}
		else {
			$xml = JP_CONFIG_CONTENT;
		}

		if (empty($xml)) die("couldn't read configuration file.");
		
		if (isset($jpaginate_replacements) && !empty($jpaginate_replacements)) 
				$xml = replaceConfigVars($xml);
		
		$res = new simpleXmlElement($xml);
		
		if ($res == false) die("configuration file is not well-formed");
		
		if (!$res) die("configuration file is empty or bad formated");
		
		if (!isset($res->pagination->showNextLink))   $res->pagination->showNextLink  = "1";
		if (!isset($res->pagination->showFirstLink))  $res->pagination->showFirstLink  = "1";
		if (!isset($res->pagination->showLastLink))   $res->pagination->showLastLink  = "1";
		if (!isset($res->pagination->showPrevLink))   $res->pagination->showPrevLink  = "1";
		if (!isset($res->pagination->centerGroup))    $res->pagination->centerGroup  = "3";
		if (!isset($res->pagination->leftGroup))      $res->pagination->leftGroup  = "3";
		if (!isset($res->pagination->rightGroup))     $res->pagination->rightGroup  = "3";
		
		
		$num = count($res->plugins->plugin);
		for ($i=0; $i<$num; $i++)
		{
			if (isset($res->plugins->plugin[$i]->name) && !empty($res->plugins->plugin[$i]->name))
				$plugin_conf[strval($res->plugins->plugin[$i]->name)] = $res->plugins->plugin[$i];
		}
		
		return $res;
	}
	else {die("jpaginate configuration file is missed or not defined");}
}


function replaceConfigVars($data) 
{
	global $jpaginate_replacements;
	
	foreach ($jpaginate_replacements as $key => $value)
	{
		$data = str_replace("(*".$key."*)", $value, $data);
	}
	
	// replace vars with empty values
	$start = strpos($data,"(*");
	$end = strpos($data,"*)");
	//$i = 0;
	while ($start !== false  && $end !== false) 
	{
		$field = substr($data, $start+2, $end - $start - 2);
		
		$data = str_replace("(*".$field."*)", "", $data);
		$start = strpos($data,"(*");
		$end = strpos($data,"*)");
		//echo $data; 
		//echo "<br>"; $i++; if ($i>50) die();
	}
	
	return $data;
 
}
?>