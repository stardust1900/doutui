<?php
if(is_file('oauth_token.serialize')) {

    session_start();
    require_once('config.php');
    require_once('OAuthClient.class.php');
    require_once('OAuthException.class.php');
    require_once('OAuthUtil.class.php');
    require_once('OAuthRequest.class.php');

    //fetch the access token
    $token_array = unserialize(file_get_contents('oauth_token.serialize'));

    //step 4  test douban api . now we try to create a broadcast
    //you need to signature in every request!!!!
    $o = new OAuthClient();
    $o->setParam('oauth_consumer_key', $config['oauth_consumer_key']);  //set your consumer key
    $o->setParam('oauth_token', $token_array['oauth_token']);
    $o->setParam('http_method', OAuthRequest::POST);                 //POST
    $o->setUrl('http://api.douban.com/miniblog/saying');  //broadcast api url. see http://www.douban.com/service/apidoc/reference/miniblog#添加广播
    $o->signature($config['oauth_consumer_secret'], $token_array['oauth_token_secret']);  //your consumer secret and access token secret ; don't forget signature.

    //post data
    $data = '<?xml version=\'1.0\' encoding=\'UTF-8\'?>'.
                '<entry xmlns:ns0="http://www.w3.org/2005/Atom" '.
                    'xmlns:db="http://www.douban.com/xmlns/">'.
                    '<content>Hello! This broadcast is posted by OAuth!</content>'.   //content
                '</entry>';

    $request = new OAuthRequest();

    //request by OAuthClient
    $response = $request->requestByOAuthClient($o, $data, $config['realm']); //realm is required in douban.


    var_dump($response);

} else {
    die('you need to get access token first! <a href="index.php">click here</a> to do it.');
}