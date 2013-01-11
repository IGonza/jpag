<?php

function jp_flagged ($val)
{
	if(!empty($val)){return '<image src="/images/icons/star_icon.gif" border="0">';}else{ return '<image src="/images/icons/star_icon_faded.gif" border="0">'; }
}

?>