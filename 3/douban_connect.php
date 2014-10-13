<?php
session_start();
require_once('config.php');
require_once('OAuthClient.class.php');
require_once('OAuthException.class.php');
require_once('OAuthUtil.class.php');
require_once('OAuthRequest.class.php');

//step 1 get the request token
$o = new OAuthClient();
$o->setParam('oauth_consumer_key', $config['oauth_consumer_key']);  //set your consumer key
$o->setUrl($config['oauth_request_token']);  // reuest token url
$url = $o->getRequestToken($config['oauth_consumer_secret']);  //your consumer secret

$request = new OAuthRequest();
$response = $request->request($url);

//echo $response;
//parse response
parse_str($response['content']);
//save it
$_SESSION['oauth_token'] = $oauth_token;
$_SESSION['oauth_token_secret'] = $oauth_token_secret;

//echo $oauth_token;
//step 2 : authorize
$o->setParam('oauth_token', $oauth_token);  //set your oauth_token that was fetched in last step
$o->setParam('oauth_callback', $config['oauth_callback']);  //your callback
$o->setUrl($config['oauth_authorize']);  // authorize url

header("Location:".$o->authorize());  //let's go