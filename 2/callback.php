<?php

session_start();
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );
header('Content-Type:text/html; charset=utf-8');


$o = new SaeTOAuth( WB_AKEY , WB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );

$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;

$_SESSION['last_key'] = $last_key;

//echo "sina_oauth_token : ".$_SESSION['last_key']['oauth_token'];
//echo "sina_oauth_token_secret : ".$_SESSION['last_key']['oauth_token_secret'];

$sina_oauth_token = $_SESSION['last_key']['oauth_token'];
$sina_oauth_token_secret = $_SESSION['last_key']['oauth_token_secret'];
$db_uid = $_SESSION["db_uid"];

$mysql = new SaeMysql();
$sql = "update sync_user set flag = 1, sina_oauth_token='".$sina_oauth_token."', sina_oauth_token_secret = '".$sina_oauth_token_secret."' where db_uid = '".$db_uid."'";
 $mysql->runSql( $sql );
 if( $mysql->errno() != 0 )
 {
     die( "Error:" . $mysql->errmsg() );
	 echo '授权失败,<a href="main.php?douban_uid='.$db_uid.'>返回</a>';
 }
 
 $mysql->closeDb();
 echo '授权完成,<a href="main.php?douban_uid='.$db_uid.'">返回</a>';
?>
<!--授权完成,<a href="weibolist.php">进入你的微博列表页面</a>-->
