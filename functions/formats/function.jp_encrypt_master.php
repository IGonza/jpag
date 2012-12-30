<?php


function jp_encrypt_master($val){
	return '<a href="http://'.URLSUPPORT.'/master_login.php?token='.$val.'" target="_blank"><img src="/images/icons/login_icon.png" width="12"> Login</a>';
}

?>