<?php
session_start();
require_once('config.php');
require_once('OAuthClient.class.php');
require_once('OAuthException.class.php');
require_once('OAuthUtil.class.php');
require_once('OAuthRequest.class.php');
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );
header("Content-Type: text/html; charset=utf-8");

$url = 'http://api.douban.com/people/stardust1900';

   // 初始化一个 cURL 对象  
    $curl = curl_init();
    // 设置你需要抓取的URL  
    curl_setopt($curl, CURLOPT_URL, $url);
   // 设置header  
    curl_setopt($curl, CURLOPT_HEADER, false);  
   // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。  
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
   // 运行cURL，请求网页  
   $data = curl_exec($curl);  
  // 关闭URL请求  
   curl_close($curl); 
    $data = preg_replace('/([<<\/?])([1-z0-9]+):([1-z0-9]+)/i','$1$2_$3',$data);
	//var_dump($data);
	$people = simplexml_load_string($data);
	//var_dump($people);

	echo "欢迎你 ".$people->title."!"."   <img src='".$people->link[2]->attributes()."' />";
	//echo $people->link[2]->attributes()["href"];

//if( isset($_SESSION['last_key']) ) header("Location: weibolist.php");


$o = new SaeTOAuth( WB_AKEY , WB_SKEY  );

$port = '';

//echo $_SERVER['SERVER_PORT']; 

if( $_SERVER['SERVER_PORT'] != 80 ) $port = ':'.$_SERVER['SERVER_PORT'];
$proto=is_https()?'https://':'http://';
$keys = $o->getRequestToken();
$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $proto .$_SERVER['HTTP_HOST']/* $_SERVER['HTTP_APPVERSION'] .'.'. $_SERVER['HTTP_APPNAME'] . '.sinaapp.com' . $port*/ . '/callback.php');

$_SESSION['keys'] = $keys;

?>
<br/>
<a href="<?=$aurl?>"><img src="sync_sina.png" align="middle"/></a>
