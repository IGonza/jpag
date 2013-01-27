$(document).ready(function() {

	$('.jp_filter').keyup(function(e) {
		if(e.keyCode == 13) { changeSearchCriteria(); } /*13=enter*/
	});
	
    /*check default value of .multifilter select, show corresponding boxes if value is greater than 0*/
	var multifilterID = $('select.jp_multifilter').attr('id');
	if( $('#'+multifilterID).val() > 0 ){ $('#'+multifilterID+'_2,#'+multifilterID+'_3').removeAttr('disabled').show(); }
	
	$('select.jp_multifilter').change(function() {
		var thisID = $(this).attr('id'); /*grab defaultID*/
		
		if( $(this).val()=='' ){ 
        	$('#'+thisID+'_2,#'+thisID+'_3').attr('disabled','disabled').hide(); return false; 
        }else{ 
        	$('#'+thisID+'_2,#'+thisID+'_3').removeAttr('disabled').show(); 
        }
		
		var watermark = $('#'+thisID+' option:selected').attr('placeholder');
		if(watermark != undefined){ 
          	///alert("a watermark does exist for: "+thisID+" watermark: "+watermark); 
            $('#'+thisID+'_3').attr('placeholder',watermark);
          }else{
          	///alert("no watermark exists for: "+thisID); 
          	$('#'+thisID+'_3').removeAttr('placeholder').focus(); 
        }
        	
            
		/* comparison operators */
		var compare = $('#'+thisID+' option:selected').attr('compare');
		var brokencompare=compare.split(',');
		var comparenum = brokencompare.length;
		$('#'+thisID+'_2').hide(); // hide the comparison operators dropdown
		$('#'+thisID+'_2 option').hide();
		$.each(brokencompare, function(index, value) { 
		  //alert(index + ': ' + value); 
			if(index==0){ $('#'+thisID+'_2').val(value); }
			$('#'+thisID+'_2 option[value='+value+']').show();
		
		});
		if(comparenum >1){ $('#'+thisID+'_2').show(); } // hide the comparison operators

	});
	
	$('.jp_multifilter').keyup(function(e) {
		if(e.keyCode == 13) { changeSearchCriteria(); }
	});
	
	$('.jp_filter.focused').focus();
	<?php jp_hook_start_function(); ?>

});

function openjpdebug(){
	$("#jp_debug_table").toggle();
}

function loadPaginationTable(numrows) {
	$("#status_indicator").attr("style", "visibility:visible");

	max_results = $("#jp_max_results").val();
	cur_page = $("#jp_curpage").val();
	search_value = "";
	multisearch_value = "";
	jp_sort_val = $("#jp_sort").val();
	jp_sortd_val = $("#jp_sortd").val();
	jp_gets_val = $("#jp_gets").val();

	filters = $(".jp_filter");
	filters.each(function (i) {
		if ($(this).val() != "undefined") search_value = search_value + $(this).val() + "|||";
	});
	
	multifilters = $(".jp_multifilter");
	multifilters.each(function (i) {
		if ($(this).val() != "undefined") multisearch_value = multisearch_value + $(this).val() + "|||";
	});


	
	var url = "<?=SERVER_FILE?>?load=data";
	var data = "max_results=" + max_results + "&cur_page=" + cur_page + "&multisearch_val=" + multisearch_value + "&search_val=" + search_value + "&jp_sort=" + jp_sort_val + "&jp_sortd=" + jp_sortd_val + "&" + jp_gets_val;
	
	$.ajax({
		url: url,
		data: data,
		cache: false,
		type: "GET",
	  	success: function(res){
			
			var content = res.split("|||||");
			
			$("#jpaginate_contents").html(content[0]);
			
			//alert(content[1]);
			$("#jp_total_rows").html(content[1]);
			$(".jp_pageNumbering").html(content[2]);
			
			if (content[1] == "1") $(".jp_sort_imgs").hide();
			
			if (content[1] == "0") $("#jpaginate_buttons").attr("style", "visibility:hidden");
			else $("#jpaginate_buttons").attr("style", "visibility:visible");
			
			if (parseInt(content[1]) < max_results) {
				//$("#jp_max_results_span").attr("style", "visibility:hidden");
				//$(".jp_of_text").attr("style", "visibility:hidden");
				$(".jp_results_pages").hide();
			}
			else {
				$("#jp_max_results_span").attr("style", "visibility:visible");
				$(".jp_of_text").attr("style", "visibility:visible");
				$(".jp_results_pages").show();
			}
			
			
			$("#status_indicator").attr("style", "visibility:hidden");
		
			$(document).ready(function() {
				
				<?php jp_hook_addjs_after_get_results(); ?>
								
				fixTableStyle();
			});
				
			} // end success
	});	 //end .ajax
	
}



function jp_sendRequest(vars, f_name)
{
	$("#status_indicator").attr("style", "visibility:visible");
	jp_gets_val = $("#jp_gets").val();
	$.ajax({
		url: "<?=SERVER_FILE?>?load=pl_request",
		data: vars + "&" + jp_gets_val,
		cache: false,
		type: "GET",
	  	success: function(res){
	  		str = f_name + "(" + res + ")";
            //alert(str);
			eval(str);
		}
	});
}

function changePage(pageNumber) {
	$("#jp_curpage").val(pageNumber);
	loadPaginationTable(0);
}

function changeMaxResults(number) {
	$("#jp_max_results").val(number);
	loadPaginationTable(0);
}

function changeSearchCriteria()
{
	$("#jp_curpage").val("1");
	loadPaginationTable(0);
}

function changeSortCriteria(id, d)
{
	$("#jp_sort").val(id);
	$("#jp_sortd").val(d);
	loadPaginationTable(0);
}

function changeFilters(f_values)
{
	//alert(f_values[0]);
	//alert(f_values[1]);
	filters = $(".jp_filter");
	filters.each(function (i) {
		if (f_values[i] != "undefined")
		{
			//alert(f_values[i]);
			$(this).val(f_values[i]);
        }
	});
}

function fixTableStyle()
{
<?php 
jp_hook_rebuildGenericStyle();
jp_hook_addStyleEffects(); 
?>
}

<?php jp_hook_addjs_functions(); ?>
