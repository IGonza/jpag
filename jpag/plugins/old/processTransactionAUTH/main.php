<?

function processTransactionAUTH_content($data, $plugin_conf)
{
	$class = isset($data['class']) ? $data['class'] : "";
	
	if($data==1){ $showtext = '<div class="divlink1L main16x16 icon561"></div><div class="divlink2L '.$class.'" id="'.rand().'">pre-auth</div>'; }
	elseif($data==2){ $showtext = '--invoice'; }
	elseif($data==3){ $showtext = '--check draft'; }
	
	return $showtext;
}

function processTransactionAUTH_addjs_after_get_results($plugin_conf)
{
	$res =
	'
	$(".processtransactionAUTH").click(function(){
		var cell = $(this).closest("td"); 
		var parenttr = $(this).closest("tr").attr("id");
		var brokenstring=parenttr.split("_");
		var orderid = brokenstring[1];  //var orderid = brokenstring[brokenstring.length-1];
		//alert("orderid=" + orderid); return false;
		if(orderid==\'\'){alert("orderid is missing");return false;}
		$($(this).parent()).html(\'<img src="images/loaders/ajax-loader-red.gif" alt="loading..." /> contacting gw..\'); 
		$.ajax({data:"formId=processtransaction&type=auth&orderid="+orderid,success:function(response){eval(response);}});
		return false;
	});
	';
	return $res;	
}


function processTransactionAUTH_addjs_functions($plugin_conf)
{
		$res = '
			function jp_processTransactionAUTH_openrecord(data)
			{
					//alert("data: "+data);
					$("#details_"+data).toggle();
			}
		';
	return $res;
}


?>
