<?php
//only pass the adminid
function jp_coreAdminAvatar ($adminid)
{
	
	$avatarpath = get_admin_avatar($adminid,4);
	//trigger_error("$adminid, $avatarpath");
	
	return '<div>'.displayimage($avatarpath).'</div>';
}

?>