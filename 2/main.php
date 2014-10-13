<?php
session_start();
require_once('config.php');
require_once('saet.ex.class.php');
header("Content-Type: text/html; charset=utf-8");
/*if(empty($_GET['douban_uid'])){
	$oauth_token = $_SESSION['oauth_token'];
	$oauth_token_secret = $_SESSION['oauth_token_secret'];

	//echo $oauth_token;
	//echo "hahahah \n";
	$o = new OAuthClient();
	$o->setParam('oauth_consumer_key', $config['oauth_consumer_key']);  //set your consumer key
	$o->setParam('oauth_token', $oauth_token);  //set your token
	$o->setUrl($config['oauth_access_token']);  // reuest token url

	$url = $o->getAccessToken($config['oauth_consumer_secret'], $oauth_token_secret); 

	$request = new OAuthRequest();
	$response = $request->request($url);

	echo $response['content'];
	$token_array = array();
	parse_str($response['content'], $token_array);

	//clear the sessions
	$_SESSION['oauth_token'] = $_SESSION['oauth_token_secret'] = '';
	//you can save other parameters
	$_SESSION['db_uid'] = $token_array['douban_user_id'];
	$db_uid = $_SESSION['db_uid'];
}else{
	$db_uid = $_GET['douban_uid'];
}
*/
$db_uid = $_GET['douban_uid'];
$_SESSION['db_uid'] = $db_uid;
if($db_uid){
    $url = 'http://api.douban.com/people/'.$db_uid;

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
	echo "欢迎你 "."   <img src='".$people->link[2]->attributes()."' />".$people->title."! <br/> <br/>";

    $mysql = new SaeMysql();
    $sql = "select * from sync_user where db_uid = ".$db_uid;
	//echo $sql;
	$record = $mysql->getLine($sql);
    if($record){
		if($record["flag"]==1){
			echo '已同步豆瓣推荐到新浪微博  <a href="cancel.php">取消</a>';
		}else if($record["flag"]==0){
			 $o = new SaeTOAuth( WB_AKEY , WB_SKEY  );
			$port = '';
			//echo $_SERVER['SERVER_PORT']; 
			if( $_SERVER['SERVER_PORT'] != 80 ) $port = ':'.$_SERVER['SERVER_PORT'];
			$proto=is_https()?'https://':'http://';
			$keys = $o->getRequestToken();
			$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $proto .$_SERVER['HTTP_HOST']. '/callback.php');
			$_SESSION['keys'] = $keys;
			
			echo '同步豆瓣推荐到新浪微博<a href="'.$aurl.'"><img src="sync_sina.png" align="middle" style="border:none"/></a>';
		}else if($record["flag"]==2){
			echo '同步豆瓣推荐到新浪微博<a href="reconnect.php"><img src="sync_sina.png" align="middle" style="border:none"/></a>';
		}
      
	}else{
		$insertSql = "insert into sync_user (db_uid, db_oauth_token, db_oauth_token_secret) values(".$db_uid.",'".$oauth_token."','".$oauth_token_secret."')";
		$mysql->runSql($insertSql);
			$o = new SaeTOAuth( WB_AKEY , WB_SKEY  );
			$port = '';
			//echo $_SERVER['SERVER_PORT']; 

			if( $_SERVER['SERVER_PORT'] != 80 ) $port = ':'.$_SERVER['SERVER_PORT'];
			$proto=is_https()?'https://':'http://';
			$keys = $o->getRequestToken();
			$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $proto .$_SERVER['HTTP_HOST'] . '/callback.php');
			$_SESSION['keys'] = $keys;
		echo '同步豆瓣推荐到新浪微博<a href="'.$aurl.'"><img src="sync_sina.png" align="middle" style="border:none"/></a>';
	}
	$mysql->closeDb();

}else{
     header("Location:index.php");
}
?>
