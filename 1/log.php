<?php
header("Content-Type: text/html; charset=utf-8");
	$s = new SaeStorage();
	$domain = 'data';
	$filename = 'log.txt' ;
	$content = $s->read($domain,$filename);

	if($content){
		echo $content ;
	}else {
		echo '-------nothing-------- ';
	}
	
?>