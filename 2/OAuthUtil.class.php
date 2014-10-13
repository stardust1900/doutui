<?php
/***************************************************************************
 *
 * Copyright (c)2009 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/

/**
 * OAuth 工具类
 *
 * @package	oauth
 * @author	dongliqiang(dongliqiang@baidu.com)
 * @version	$Revision: 1.0 $
 **/
class OAuthUtil {

	/**
	 * URL-encode according to RFC 3986
	 * 
	 * @param array|string $input
	 * @return string
	 */
	public static function urlencode($input) {
		if (is_array($input)) {
			return array_map(array('OAuthUtil', 'urlencode'), $input);
		} else if (is_scalar($input)) {
			return str_replace(array('+', '%7E'), array(' ', '~'), rawurlencode($input));
		} else {
			return '';
		}
	}

	/**
	 * Decode URL-encoded strings
	 * 
	 * @param string $string
	 * @return string
	 */
	public static function urldecode($string) {
		return urldecode($string);
	}

	/**
	 * check the string is a URL or not
	 * 
	 * @param string $url
	 * @return bool
	 */
	public static function isUrl($url) {
		if(empty($url)) {
			return false;
		}
		$urlPattern = '/^(http|https):\/\/[^\s&<>#;,"\'\?]*(|#[^\s<>;"\']*|\?[^\s<>;"\']*)$/i';
		return (bool)preg_match($urlPattern, $url);
	}
}
?>
