<?php
session_start();
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );
header('Content-Type:text/html; charset=utf-8');
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}
if($token) {
$db_uid = $_SESSION["db_uid"];
$weibo_uid = $token["uid"];
$weibo_access_token = $token["access_token"];

$mysql = new SaeMysql();
$sql = "update doutui_user set flag = 1, wb_uid='".$weibo_uid."', wb_access_token = '".$weibo_access_token."' where db_uid = '".$db_uid."'";
$mysql->runSql( $sql );
 if( $mysql->errno() != 0 )
 {
     die( "Error:" . $mysql->errmsg() );
	 echo '授权失败,<a href="main.php">返回</a>';
 }else{
 	echo '授权完成,<a href="main.php">返回</a>';
 }
 $mysql->closeDb();
 
}else{
	echo '授权失败,<a href="main.php">返回</a>';
}
?>

