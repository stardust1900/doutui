<?php

require_once('config.php');
require_once('OAuthClient.class.php');
require_once('OAuthException.class.php');
require_once('OAuthUtil.class.php');
require_once('OAuthRequest.class.php');

	class Sae_DouBan{
		const SERVER_URL = 'http://api.douban.com';
		protected $_APIKey = NULL;
		protected $_client = NULL;
        protected $req = NULL;
		public function __construct($apiKey = NULL, $secret = NULL){
        	//$this->registerPackage('Sae_DouBan');
			$this->_client = new OAuthClient($apiKey, $secret);
			$this->_APIKey = $apiKey;
			$this->req =  new OAuthRequest();
    	}

		public function getAuthorizationURL($apiKey = NULL, $secret = NULL, $callback = NULL){

		}

		public function getRequestToken(){

		}

		public function getAccessToken($key = NULL, $secret = NULL, $token = NULL){

		}

		public function programmaticLogin($tokenKey = NULL, $tokenSecret = NULL){

		}

		public function getPeople($peopleId = NULL){
			if ($peopleId !== NULL) {
				$url = self::SERVER_URL . "/people/" . $peopleId;
			}
			echo $url;
			$response = $this->req->request($url);
			$data = $response['content'];
			$data = preg_replace('/([<<\/])([1-z0-9]+):([1-z0-9]+)([>])/i','$1$2_$3$4',$data);
            $people =  simplexml_load_string($data);
			var_dump($data);
			return $people;
		}

}

?>