<?php

function jp_fileExtIcons($val){
	$separated = explode("|",$val);
	if(empty($separated[0])){ return ''; }else{ $ext = $separated[0]; } // options: url,docs
	if(empty($separated[1])){ $side = 'divlink1L '; }else{ $side = $separated[1]; }
	
	
	//divlink1L main16x16 icon1374
	if($ext=='pdf'){ $ico = 'icon1347'; }
	elseif($ext=='doc'){ $ico = 'icon1374'; }
	elseif($ext=='docx'){ $ico = 'icon1374'; }
	elseif($ext=='xlsm'){ $ico = 'icon1299'; }
	elseif($ext=='xls'){ $ico = 'icon1299'; }
	elseif($ext=='csv'){ $ico = 'icon1311'; }
	
	elseif($ext=='jpg'){ $ico = 'icon1325'; }
	elseif($ext=='gif'){ $ico = 'icon1325'; }
	elseif($ext=='png'){ $ico = 'icon1325'; }
	elseif($ext=='ico'){ $ico = 'icon1325'; }
	elseif($ext=='html'){ $ico = 'icon1308'; }
	elseif($ext=='php'){ $ico = 'icon1341'; }
	
	elseif($ext=='exe'){ $ico = 'icon1288'; }
	elseif($ext=='zip'){ $ico = 'icon1375'; }
	elseif($ext=='swf'){ $ico = 'icon1313'; }
	elseif($ext=='ppt'){ $ico = 'icon1342'; }
	elseif($ext=='pptx'){ $ico = 'icon1342'; }
	elseif($ext=='pptm'){ $ico = 'icon1342'; }
	else{ $ico = 'icon1307'; }
	
	//return $icon = '<img src="/images/icons/filetypes/16px/pdf.png" />';
	return $icon = '<div class="'.$side.' main16x16 '.' '.$ico.'"></div>';
		
}
?>