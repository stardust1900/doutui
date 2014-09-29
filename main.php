<?php
session_start();
require_once('config.php');
require_once('OAuthClient.class.php');
require_once('OAuthException.class.php');
require_once('OAuthUtil.class.php');
require_once('OAuthRequest.class.php');

$oauth_token = $_SESSION['oauth_token'];
$oauth_token_secret = $_SESSION['oauth_token_secret'];
echo $_SESSION['db_uid'];
if($_SESSION['db_uid']){
echo "hahaha";
$douban_uid = $_SESSION['db_uid'];
}else{
 echo "xxxxxxxx";
}

?>
