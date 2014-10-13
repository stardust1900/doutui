<?php
session_start();
require_once('config.php');
require_once('OAuthClient.class.php');
require_once('OAuthException.class.php');
require_once('OAuthUtil.class.php');
require_once('OAuthRequest.class.php');
	session_start();
	$user_id = $_SESSION["user_id"];

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

	//echo $response['content'];
	$token_array = array();
	parse_str($response['content'], $token_array);

	//clear the sessions
	$_SESSION['oauth_token'] = $_SESSION['oauth_token_secret'] = '';
	//you can save other parameters
	$_SESSION['db_uid'] = $token_array['douban_user_id'];
	$db_uid = $_SESSION['db_uid'];
  $mysql = new SaeMysql();
	$sql = "select * from sync_user where 1!=1 and db_uid = '".$db_uid."'";
	$data = $mysql ->getLine($sql);
	if(!$data){
		$insertSql = "insert into sync_user (db_uid, db_oauth_token, db_oauth_token_secret) values('".$db_uid."','".$oauth_token."','".$oauth_token_secret."')";
		$mysql->runSql($insertSql);
		$tableSql = "CREATE TABLE `rec".$db_uid."` (
			  `id` int(11) NOT NULL auto_increment,
			  `db_uid` varchar(100) default NULL,
			  `api_id` varchar(500) default NULL,
			  `title` text,
			  `url` text,
			  `comment` text,
			  `published` datetime default NULL,
			  `content` text,
			  `category` varchar(100) default NULL,
			  `title_code` text,
			  `comment_code` text,
			  PRIMARY KEY  (`id`),
			  FULLTEXT KEY `fulltext_title_comment` (`title_code`,`comment_code`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$mysql->runSql($tableSql);
		if( $mysql->errno() != 0 ){
			die( "Error:".$mysql->errmsg());
		}

		//修改用户表绑定状态
		$updateSql = "update t_user set bind=1, db_uid = '".$db_uid."',db_oauth_token = '".$oauth_token."', db_oauth_token_secret = '".$oauth_token_secret."' where user_id = $user_id";

		$mysql->runSql($updateSql);

		$mysql->closeDb();
		//增加任务同步推荐目录
		 $queue = new SaeTaskQueue('doutui_rec_1');//顺序队列
		 for($i=1;$i<=30;$i++){
			 $temp = ($i-1)*50+1;
			 $queue->addTask("http://3.doutui.sinaapp.com/sync_rec.php","start-index=$temp"."&max-results=50&douban_uid=$db_uid");
	     }
    //将任务推入队列
	$ret = $queue->push();
	var_dump($ret);
	if ($ret === false)
		var_dump($queue->errno(), $queue->errmsg());
	
	}
	
	 echo "<SCRIPT LANGUAGE='JavaScript'>"; 
	echo "location.href='setting.php?douban_uid=$db_uid&user_id=$user_id'"; 
	echo "</SCRIPT>";
?>