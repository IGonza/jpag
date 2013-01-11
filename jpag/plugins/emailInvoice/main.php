<?

function emailInvoice_content($data, $plugin_conf)
{
	$class = isset($data['class']) ? $data['class'] : ""; // this is part of the <content> ?
	
	$separated = explode("|",$data);
	if($separated[0]==''){ return "missing orderid"; }else{ $orderid = $separated[0]; }
	if($separated[1]==''){ return "missing orderstatus"; }else{ $orderstatus = $separated[1]; }
	if($separated[2]==''){ return ''; }
	
//{*orderid*}|{*massinvoiceid*}|{*email*}	
	$showtext='';
	if(in_array($orderstatus,array(3,7))){
		$showtext = '<div class="divlink1L main16x16 icon1987"></div><div class="divlink2L emailinvoice" id="'.rand().'">email message</div>'; 
	}
	
	return $showtext;
}

function emailInvoice_addjs_after_get_results($plugin_conf)
{
	$res =
	'
	$(".emailinvoice").click(function(){
		var cell = $(this).closest("td"); 
		var parenttr = $(this).closest("tr").attr("id");
		var brokenstring=parenttr.split("_");
		var orderid = brokenstring[1];  //var orderid = brokenstring[brokenstring.length-1];
		//alert("orderid=" + orderid); return false;
		if(orderid==\'\'){alert("orderid is missing");return false;}
		$($(this).parent()).html(\'<img src="images/loaders/ajax-loader-red.gif" alt="loading..." /> sending...\'); 
		$.ajax({data:"formId=emailinvoice&orderid="+orderid,success:function(response){eval(response);}});
		return false;
	});
	';
	return $res;	
}


/*function emailInvoice_addjs_functions($plugin_conf)
{
		$res = '
			function jp_processTransaction_openrecord(data)
			{
					//alert("data: "+data);
					$("#details_"+data).toggle();
			}
		';
	return $res;
}*/


?>
