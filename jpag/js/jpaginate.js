$(document).ready(function() {

    {*jp_ready*}

});



function jp_loadData(numrows) {

    $("#jp_loading").attr("style", "visibility:visible");

    jp_max_results = $("#jp_max_results").val();
    jp_cur_page = $("#jp_curpage").val();


    jp_sort_val = $("#jp_sort").val();
    jp_sortd_val = $("#jp_sortd").val();
    jp_gets_val = $("#jp_gets").val();
    /*
        search_value = "";
	multisearch_value = "";


	filters = $(".jp_filter");
	filters.each(function (i) {
		if ($(this).val() != "undefined") search_value = search_value + $(this).val() + "|||";
	});

	multifilters = $(".jp_multifilter");
	multifilters.each(function (i) {
		if ($(this).val() != "undefined") multisearch_value = multisearch_value + $(this).val() + "|||";
	});
     */


    var url = "<?=SERVER_FILE?>?load=data";
    var data = "max_results=" + max_results +
        "&cur_page=" + cur_page +
        "&multisearch_val=" + multisearch_value +
        "&search_val=" + search_value +
        "&jp_sort=" + jp_sort_val +
        "&jp_sortd=" + jp_sortd_val +
        "&" + jp_gets_val;

    $.ajax({
        url: url,
        data: data,
        cache: false,
        type: "GET",
        success: function(res){

            var content = res.split("|||||");

            $("#jpaginate_contents").html(content[0]);

            $("#jp_total_rows").html(content[1]);
            $(".jp_pageNumbering").html(content[2]);

            if (content[1] == "1") $(".jp_sort_imgs").hide();

            if (content[1] == "0") $("#jpaginate_buttons").attr("style", "visibility:hidden");
            else $("#jpaginate_buttons").attr("style", "visibility:visible");

            if (parseInt(content[1]) < max_results) {

                $(".jp_results_pages").hide();
            }
            else {
                $("#jp_max_results_span").attr("style", "visibility:visible");
                $(".jp_of_text").attr("style", "visibility:visible");
                $(".jp_results_pages").show();
            }


            $("#jp_loading").attr("style", "visibility:hidden");

            $(document).ready(function() {

                {*jp_hook_addjs_after_get_results*}

                fixTableStyle();
            });

        } // end success
    });	 //end .ajax

}



function jp_sendRequest(vars, f_name)
{
    $("#jp_loading").attr("style", "visibility:visible");
    jp_gets_val = $("#jp_gets").val();
    $.ajax({
        url: "<?=SERVER_FILE?>?load=pl_request",
        data: vars + "&" + jp_gets_val,
        cache: false,
        type: "GET",
        success: function(res){
            str = f_name + "(" + res + ")";
            eval(str);
        }
    });
}

function changePage(pageNumber) {
    $("#jp_curpage").val(pageNumber);
    jp_loadData(0);
}

function changeMaxResults(number) {
    $("#jp_max_results").val(number);
    jp_loadData(0);
}

function changeSearchCriteria()
{
    $("#jp_curpage").val("1");
    jp_loadData(0);
}

function changeSortCriteria(id, d)
{
    $("#jp_sort").val(id);
    $("#jp_sortd").val(d);
    jp_loadData(0);
}

function changeFilters(f_values)
{

    filters = $(".jp_filter");
    filters.each(function (i) {
        if (f_values[i] != "undefined")
        {
            $(this).val(f_values[i]);
        }
    });
}

function fixTableStyle()
{

    {*jp_hook_rebuildGenericStyle*}
    {*jp_hook_addStyleEffects*}

}

{*jp_hook_addjs_functions*}
