<?php
$user_id = 101;
$time = time();

//echo md5($t.'20110421'.'1');
for($i=0;$i<10;$i++){
	echo md5($user_id.$time.$i);
}
?>