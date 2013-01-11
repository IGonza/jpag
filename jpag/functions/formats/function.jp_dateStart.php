<?php

function jp_dateStart($val){


	return convertLocalToServerTime($val.' 00:00:00', 1, 1);

}

?>