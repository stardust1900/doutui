<?php
$url = "";
if(isset($_GET['url'])) {
	$url = $_GET['url'];
}
writeLog("taskqueue execute error ".$url);

function writeLog($log){
        $s = new SaeStorage();
        $domain = 'data' ;          
        $filename = 'log.txt' ; 
        $s->write($domain,$filename,$log);
}
