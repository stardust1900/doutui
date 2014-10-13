<?php
/***************************************************************************
 *
 * Copyright (c)2009 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/

/**
 * OAuth Client
 *
 * @package	oauth
 * @author	dongliqiang(dongliqiang@baidu.com)
 * @version	$Revision: 1.0 $
 **/
class OAuthClient {

	/**
	 * OAuth version
	 * 
	 * @var string
	 */
    public static $oauth_version = '1.0';

	/**
	 * Http method
	 *
	 * @var string
	 */
	public $http_method = 'GET';

	/**
	 * the url to be request
	 * 
	 * @var string
	 */
	public $url = '';

	/**
	 * parameters
	 * 
	 * @var array
	 */
	public $params = array();

	/**
	 * base string
	 * 
	 * @var string
	 */
	public $base_string = '';

	/**
	 * the client request type
	 * it can be 'request_token' , 'authorize' or 'access_token' only
	 *
	 * @var string
	 */
	protected $request_type = 'request_token';

	/**
	 * construct
	 */
	public function  __construct() {
		//default parameters
		$this->params = array(
			'oauth_version' => self::$oauth_version,
			'oauth_nonce' => $this->getNonce(),
			'oauth_timestamp' => $this->getTimestamp(),
			'oauth_signature_method' => 'HMAC-SHA1',
		);
	}

	/**
	 * set the OAuth Client parameter
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	public function setParam($name, $value) {
		switch ($name) {
			//check the signature method
			case 'oauth_signature_method':
				if(in_array($value, array('HMAC-SHA1', 'MD5', 'PLAINTEXT'))) {
					$this->params['oauth_signature_method'] = $value;
				}
				break;
			//check the http method
			case 'http_method':
				if(in_array($value, array('GET', 'POST'))) {
					$this->http_method = $value;
				}
				break;
			case 'oauth_version':
				//we can not change the OAuth version now!!!
				break;
			//callback is URL only
			case 'oauth_callback':
				if(OAuthUtil::isUrl($value)) {
					$this->params['oauth_callback'] = OAuthUtil::urlencode($value);
				} else {
					throw new OAuthException("invalid oauth_callback url: $value");
				}
				break;
			default:
				$this->params[$name] = $value;
		}
		
	}

	/**
	 * set the request url
	 * 
	 * @param string $url
	 */
	public function setUrl($url) {
		if(OAuthUtil::isUrl($url)) {
			$this->url = $url;
		} else {
			throw new OAuthException("invalid $this->request_type url: $url");
		}
	}

	/**
	 * step 1 : get the request token
	 *
	 * @param string $key_secret
	 * @return string
	 */
	public function getRequestToken($key_secret) {
		$this->request_type = 'request_token';
		$this->signature($key_secret);
		if($this->checkParams()) {
			return $this->getRequestUrl();
		}
	}

	/**
	 * step 2 : authorize
	 *
	 * @return string
	 */
	public function authorize() {
		$this->request_type = 'authorize';
		if($this->checkParams()) {
			return $this->getRequestUrl();
		}
	}

	/**
	 * step 3 : get the access token
	 * 
	 * @param string $key_secret
	 * @param string $token_secret
	 * @return string
	 */
	public function getAccessToken($key_secret, $token_secret) {
		$this->request_type = 'access_token';
		$this->signature($key_secret, $token_secret);
		if($this->checkParams()) {
			return $this->getRequestUrl();
		}
	}

	/**
	 * get the request url
	 * 
	 * @return string
	 */
	public function getRequestUrl() {
		$post = $this->bulidHttpQuery($this->params);
		$url = $this->parseUrl();
		if (!empty($post)) {
		  $url .= '?'.$post;
		}
		return $url;
	}

	/**
	 * get the OAuth headers
	 * 
	 * @param string $realm
	 * @return string
	 */
	public function getOAuthHeaders($realm = null) {
		if($realm) {
			$out = 'Authorization: OAuth realm="' . OAuthUtil::urlencode($realm) . '",';
		} else {
			$out = 'Authorization: OAuth ';
		}

		$params = array();
		ksort($this->params);
		foreach ($this->params as $k => $v) {
			//we don't need other parameters
			if (substr($k, 0, 5) != "oauth") continue;
			
			$params[] = OAuthUtil::urlencode($k) . '="' .
						OAuthUtil::urlencode($v) . '"';
		}

		return $out . implode(',', $params);
	}

	/**
	 * Check whether the parameters integrity
	 *
	 * @return bool
	 */
	protected function checkParams() {
		if($this->request_type == 'request_token') {
			$requires = array(
				'oauth_consumer_key',
				'oauth_signature_method',
				'oauth_timestamp',
				'oauth_nonce',
				'oauth_signature',
				'oauth_version',
			);
		} else if($this->request_type == 'authorize') {
			$requires = array(
				'oauth_token',
				'oauth_callback',
			);
		} else if($this->request_type == 'access_token') {
			$requires = array(
				'oauth_consumer_key',
				'oauth_signature_method',
				'oauth_timestamp',
				'oauth_nonce',
				'oauth_signature',
				'oauth_token',
				'oauth_version',
			);
		}

		foreach($requires as $value) {
			if(empty($this->params[$value])) {
				throw new OAuthException('missing required parameter : ' . $value);
			}
		}

		if(empty($this->url)) {
			throw new OAuthException("missing $this->request_type url .");
		}

		return true;
	}

	/**
	 * signature
	 * 
	 * @param string $key_secret
	 * @param string $token_secret
	 */
	public function signature($key_secret, $token_secret = null) {
		$signature_class = 'OAuthSignatureMethod_' . str_ireplace('-', '_', $this->params['oauth_signature_method']);
		$signature_file = dirname(__FILE__) . '/OAuthSignatureMethod/' . $signature_class . '.class.php';
		if(is_file($signature_file)) {
			require_once($signature_file);
			$sc = new $signature_class();
			$signature = $sc->signature($this->getBaseString(), $key_secret, $token_secret);
			$this->params['oauth_signature'] = $signature;
		} else {
			throw new OAuthException('can not find the signature file.');
		}
	}

	/**
	 * get the http method
	 *
	 * @return string
	 */
	protected function getHttpMethod() {
		return strtoupper($this->http_method);
	}

	/**
	 * parse the request url
	 * 
	 * @return string
	 */
	protected function parseUrl() {
		$argv = parse_url($this->url);

		$scheme = isset($argv['scheme']) ? $argv['scheme'] : 'http';
		$port = isset($argv['port']) ? $argv['port'] : (($scheme == 'https') ? '443' : '80');
		$host = isset($argv['host']) ? $argv['host'] : '';
		$path = isset($argv['path']) ? $argv['path'] : '';

		if (($scheme == 'https' && $port != '443')
			|| ($scheme == 'http' && $port != '80')) {
		  $host = "$host:$port";
		}
		return "$scheme://$host$path";
	}

	/**
	 * get the base string to be signatured
	 * 
	 * @return string
	 */
	protected function getBaseString() {
		//sort param
		ksort($this->params);
		$params = array(
			$this->getHttpMethod(),
			$this->parseUrl(),
			$this->getQuery()
		);

		$params = OAuthUtil::urlencode($params);
		$this->base_string = implode('&', $params);

		return $this->base_string;
	}

	/**
	 * get the http query
	 *
	 * @return string
	 */
	protected function getQuery() {
		$params = $this->params;

		if (isset($params['oauth_signature'])) {
			unset($params['oauth_signature']);
		}

		return $this->bulidHttpQuery($params);
	}

	/**
	 * bulid the http query with parameters
	 *
	 * @param array $params
	 * @return string
	 */
	protected function bulidHttpQuery($params) {
		if(empty($params)) {
			return '';
		} else {
			$queryArr = array();
			foreach($params as $key => $value) {
				$queryArr[] = OAuthUtil::urlencode($key) . '=' . OAuthUtil::urlencode($value);
			}
			return implode('&', $queryArr);
		}
	}


	/**
	 * get a noce
	 *
	 * @return string
	 */
	protected function getNonce() {
		return md5(uniqid(mt_rand(), true));
	}

	/**
	 * get the timestamp
	 * @return int
	 */
	protected function getTimestamp() {
		return time();
	}
	
}
?>
