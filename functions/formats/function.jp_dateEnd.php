<?php

function jp_dateEnd($val){


	return convertLocalToServerTime($val.' 23:59:59', 1, 1);

}

?>