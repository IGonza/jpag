<?php

function jp_formatDateTime($date){
	if(empty($date)){ return '--------'; }
	return easy_date_time($date,1);
}

?>