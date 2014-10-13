<?php
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );
header("Content-Type: text/html; charset=utf-8");
$mysql = new SaeMysql();
$sql = "select * from sync_user";
$users = $mysql->getData($sql);
$now = time();
foreach($users as $user){
	//echo $user["db_uid"];
	//echo $user["sina_oauth_token"]."\n";
	//echo $user["sina_oauth_token_secret"]."\n";
    $c = new SaeTClient( WB_AKEY , WB_SKEY , $user["sina_oauth_token"] , $user["sina_oauth_token_secret"] );
	$ms  = $c->home_timeline();
foreach( $ms as $item ){
    echo $item['text'];
}
	$c->update("test================test");
}

?>