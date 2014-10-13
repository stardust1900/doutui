<?php

/**
 * OAuth signature implementation using PLAINTEXT
 *
 * @version $Id: OAuthSignatureMethod_PLAINTEXT.class.php,v 1.2 2010/03/05 03:35:27 zhujt Exp $
 * @author Marc Worrell <marcw@pobox.com>
 * @date  Sep 8, 2008 12:09:43 PM
 *
 * The MIT License
 *
 * Copyright (c) 2007-2008 Mediamatic Lab
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once(dirname(__FILE__).'/OAuthSignatureMethod.class.php');


class OAuthSignatureMethod_PLAINTEXT extends OAuthSignatureMethod
{
	public function name ()
	{
		return 'PLAINTEXT';
	}


	/**
	 * Calculate the signature using PLAINTEXT
	 *
	 * @param OAuthRequest request
	 * @param string base_string
	 * @param string consumer_secret
	 * @param string token_secret
	 * @return string
	 */
	function signature ( $base_string, $consumer_secret, $token_secret )
	{
		return ($consumer_secret.'&'.$token_secret);
	}
}

/* vi:set ts=4 sts=4 sw=4 binary noeol: */

?>