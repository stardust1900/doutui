<?php
session_start();
require_once('config.php');
require_once('OAuthClient.class.php');
require_once('OAuthException.class.php');
require_once('OAuthUtil.class.php');
require_once('OAuthRequest.class.php');

	$oauth_token = $_SESSION['oauth_token'];
	$oauth_token_secret = $_SESSION['oauth_token_secret'];

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
	header("Location:main.php?douban_uid=".$db_uid);
?>