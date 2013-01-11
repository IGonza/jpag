<?php

function jp_formatDateColored($date){
	//return easy_date_time($date,0);
	
	
	
	$theProcessdate = hard_date_time($date,0);
	$coloreddate = easy_date_time($date,0);
	$today = hard_date_time(date('Y-m-d H:i:s'),0);
	
	if($theProcessdate == $today){ $class='darkgreen'; }elseif($theProcessdate < $today){$class='red'; }elseif($theProcessdate > $today){$class='grey'; }
	return '<span class="'.$class.'">'.$coloreddate.'</span>';
	
}

?>