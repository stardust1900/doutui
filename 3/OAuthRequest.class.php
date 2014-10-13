<?php
/***************************************************************************
 *
 * Copyright (c)2009 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/

/**
 * OAuth Request
 *
 * @package	oauth
 * @author	dongliqiang(dongliqiang@baidu.com)
 * @version	$Revision: 1.0 $
 **/
class OAuthRequest {

	/**
	 * HTTP POST
	 */
	const POST = 'POST';

	/**
	 * HTTP GET
	 */
	const GET = 'GET';

	/**
	 * HTTP PUT
	 */
	const PUT = 'PUT';

	/**
	 * HTTP DELETE
	 */
	const DELETE = 'DELETE';

	/**
	 * construct
	 * check the curl functions
	 */
	public function  __construct() {
		if(!function_exists('curl_init')) {
			throw new OAuthException('curl is not available');
		}
	}

	/**
	 * build a http request
	 *
	 * @param string $url
	 * @param string $method
	 * @param string $headers
	 * @param string $data
	 * @return array
	 */
	public function request($url, $method = self::GET, $headers = null, $data = null) {
		if(!OAuthUtil::isUrl($url)) {
			throw new OAuthException('invalid url : ' . $url);
		}
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$scheme = parse_url($url, PHP_URL_SCHEME);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, ($scheme == 'https'));
		switch($method) {
			case 'POST':
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/atom+xml', $headers));
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case 'PUT':
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/atom+xml', $headers));
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case 'DELETE':
				curl_setopt($curl, CURLOPT_HTTPHEADER, array($headers));
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				break;
			default :
				//GET is the default
				if ($headers) {
					curl_setopt($curl, CURLOPT_HTTPHEADER, array($headers));
				}
		}
		$response = curl_exec($curl);
		if (!$response) {
			$response = curl_error($curl);
			throw new OAuthException("curl error : $response");
		}
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		return array(
			'content' => $response,
			'http_code' => $http_code,
		);
	}

	/**
	 * build a http request with OAuthClient
	 *
	 * @param OAuthClient $oc
	 * @param string $realm
	 * @param string $data
	 * @return array
	 */
	public function requestByOAuthClient(OAuthClient $oc, $data, $realm = null) {
		return $this->request($oc->url, $oc->http_method, $oc->getOAuthHeaders($realm), $data);
	}

}
?>
