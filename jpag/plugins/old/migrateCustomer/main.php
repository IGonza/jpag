<?

function migrateCustomer_content($data, $plugin_conf)
{
	$class = isset($data['class']) ? $data['class'] : "";
	
	return '<div class="divlink1L main16x16 icon561"></div><div class="divlink2L '.$class.'" id="'.rand().'">migrate</div>'; 
}

function migrateCustomer_addjs_after_get_results($plugin_conf)
{
	$res =
	'
	$(".migratecustomer").click(function(){
		var cell = $(this).closest("td"); 
		var parenttr = $(this).closest("tr").attr("id");
		var brokenstring=parenttr.split("_");
		var customerid = brokenstring[1];  //var customerid = brokenstring[brokenstring.length-1];
		//alert("customerid=" + customerid); return false;
		if(customerid==\'\'){alert("customerid is missing");return false;}
		$($(this).parent()).html(\'<img src="images/loaders/ajax-loader-red.gif" alt="loading..." /> migrating...\'); 
		$.ajax({data:"formId=migratecustomerform&customerid="+customerid,success:function(response){eval(response);}});
		return false;
	});
	';
	return $res;	
}


function migrateCustomer_addjs_functions($plugin_conf)
{
		$res = '
			function jp_migrateCustomer_openrecord(data)
			{
					//alert("data: "+data);
					$("#details_"+data).toggle();
			}
		';
	return $res;
}


?>
