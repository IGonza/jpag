<?
// no need to add:
// <script language="javascript" type="text/javascript">
// $(document).ready(function(){ 
// this will probably have a problem with brackets

function loadJSbelow_addjs_after_get_results($plugin_conf) 
{
	$num =  count($plugin_conf->code);
	//trigger_error("plugin codes: $num");
	$res = "";
	for ($i=0;$i<$num;$i++)
	{
	
		//$res .= $plugin_conf->code[$i]['js']."\n";
		//trigger_error("$i $plugin_conf->code[$i][js]");
		$res .= $plugin_conf->code[$i];
		//trigger_error("$i $plugin_conf->code[$i]");
	}
	 
	return $res;
}
?>