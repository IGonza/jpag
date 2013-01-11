<?

function processTransaction_content($data, $plugin_conf)
{
	$class = isset($data['class']) ? $data['class'] : "";
	
	if($data==1){ $showtext = '<div class="divlink1L main16x16 icon561"></div><div class="divlink2L '.$class.'" id="'.rand().'">run transaction</div>'; }
	elseif($data==2){ $showtext = '<div class="divlink1L main16x16 icon1327"></div>Invoice'; }
	elseif($data==3){ $showtext = '<div class="divlink1L main16x16 icon3108"></div>Check Draft'; }
	
	return $showtext;
}

function processTransaction_addjs_after_get_results($plugin_conf)
{
	$res =
	'
	$(".processtransaction").click(function(){
		var cell = $(this).closest("td"); 
		var parenttr = $(this).closest("tr").attr("id");
		var brokenstring=parenttr.split("_");
		var orderid = brokenstring[1];  //var orderid = brokenstring[brokenstring.length-1];
		//alert("orderid=" + orderid); return false;
		if(orderid==\'\'){alert("orderid is missing");return false;}
		$($(this).parent()).html(\'<img src="images/loaders/ajax-loader-red.gif" alt="loading..." /> contacting gw..\'); 
		$.ajax({data:"formId=processtransaction&orderid="+orderid,success:function(response){eval(response);}});
		return false;
	});
	';
	return $res;	
}


function processTransaction_addjs_functions($plugin_conf)
{
		$res = '
			function jp_processTransaction_openrecord(data)
			{
					//alert("data: "+data);
					$("#details_"+data).toggle();
			}
		';
	return $res;
}


?>
