<?php

function jp_Image ($val)
{
	if($val!='') {
			return '<img src="http://img.warplite.com/NDTAR8ZKRE/characters/'.$val.'" border="0" style="width:25px;"> ';
	}else{
			return '<img src="/images/misc/0_25x25.png" border="0"> ';
	}
}

?>