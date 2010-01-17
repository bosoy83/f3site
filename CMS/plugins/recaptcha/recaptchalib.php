<?php
/* This is a PHP library that handles calling reCAPTCHA.
 *  - Documentation and latest version
 *      http://recaptcha.net/plugins/php/
 *  - Get a reCAPTCHA API Key
 *      http://recaptcha.net/api/getkey
 *  - Discussion group
 *      http://groups.google.com/group/recaptcha
 *
 * Copyright (c) 2007 reCAPTCHA -- http://recaptcha.net
 * AUTHORS:
 *   Mike Crawford
 *   Ben Maurer
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

#The reCAPTCHA server URL's
define("RECAPTCHA_API_SERVER", "http://api.recaptcha.net");
define("RECAPTCHA_API_SECURE_SERVER", "https://api-secure.recaptcha.net");
define("RECAPTCHA_VERIFY_SERVER", "api-verify.recaptcha.net");

/* Encodes the given data into a query string
 * @param $data - array of string elements to be encoded
 * @return string - encoded request */
function _recaptcha_qsencode($data)
{
	$req = '';
	foreach($data as $key => $value)
		$req .= $key . '=' . urlencode(stripslashes($value)) . '&';

	#Cut the last '&'
	$req = substr($req,0,strlen($req)-1);
	return $req;
}

#Submits an HTTP POST to a reCAPTCHA server
function _recaptcha_http_post($host, $path, $data, $port = 80)
{
	$req = _recaptcha_qsencode($data);
	$http  = "POST $path HTTP/1.0\r\n";
	$http .= "Host: $host\r\n";
	$http .= "Content-Type: application/x-www-form-urlencoded;\r\n";
	$http .= 'Content-Length: ' . strlen($req) . "\r\n";
	$http .= "User-Agent: reCAPTCHA/PHP\r\n";
	$http .= "\r\n";
	$http .= $req;
	$resp = '';

	if(false == ($fs = @fsockopen($host, $port, $errno, $errstr, 10)))
	{
		throw new Exception('Could not open socket');
	}

	fwrite($fs, $http);
	while(!feof($fs)) $resp .= fgets($fs, 1160); #One TCP-IP packet
	fclose($fs);
	return explode("\r\n\r\n", $resp, 2);
}

/* Gets the challenge HTML
 * @param string $pubkey Public key for reCAPTCHA
 * @param string $error The error given by reCAPTCHA
 * @param boolean $use_ssl SSL?
 * @return string - HTML to be embedded
*/
function recaptcha_get_html($pubkey, $error = null, $use_ssl = false)
{
	if(empty($pubkey)
	{
		throw new Exception('To use reCAPTCHA you must get an API key from <a href="http://recaptcha.net/api/getkey">http://recaptcha.net/api/getkey</a>');
	}

	$server = $use_ssl ? RECAPTCHA_API_SECURE_SERVER : RECAPTCHA_API_SERVER;
	$errorpart = '';

	if($error) $errorpart = "&amp;error=" . $error;

	return '<script type="text/javascript" src="'. $server . '/challenge?k=' . $pubkey . $errorpart . '"></script>
	<noscript>
		<iframe src="'. $server . '/noscript?k=' . $pubkey . $errorpart . '" height="300" width="500" frameborder="0"></iframe><br/>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
	</noscript>';
}

class ReCaptchaResponse {
	public $is_valid;
	public $error;
}

#Calls an HTTP POST function to verify if the user's guess was correct
function recaptcha_check_answer($privkey, $remoteip, $challenge, $response, $extra_params = array())
{
	if(empty($privkey))
	{
		throw new Exception('To use reCAPTCHA you must get an API key from <a href="http://recaptcha.net/api/getkey">http://recaptcha.net/api/getkey</a>');
	}

	if(empty($remoteip))
	{
		throw new Exception('For security reasons, you must pass the remote ip to reCAPTCHA');
	}

	//discard spam submissions
	if(empty($challenge) || empty($response))
	{
		$recaptcha_response = new ReCaptchaResponse();
		$recaptcha_response->is_valid = false;
		$recaptcha_response->error = 'incorrect-captcha-sol';
		return $recaptcha_response;
	}

	$response = _recaptcha_http_post(RECAPTCHA_VERIFY_SERVER, '/verify',
		array(
			'privatekey' => $privkey,
			'remoteip' => $remoteip,
			'challenge' => $challenge,
			'response' => $response
		) + $extra_params);

	$answers = explode ("\n", $response [1]);
	$recaptcha_response = new ReCaptchaResponse();

	if(trim($answers[0]) == 'true')
	{
		$recaptcha_response->is_valid = true;
	}
	else
	{
		$recaptcha_response->is_valid = false;
		$recaptcha_response->error = $answers[1];
	}
	return $recaptcha_response;
}

#Gets a URL where the user can sign up for reCAPTCHA. If your application
#has a configuration page where you enter a key, you should provide a link
function recaptcha_get_signup_url($domain=null, $appname=null)
{
	return 'http://recaptcha.net/api/getkey?'._recaptcha_qsencode(array('domain'=>$domain, 'app'=>$appname));
}

function _recaptcha_aes_pad($val)
{
	$block_size = 16;
	$numpad = $block_size - (strlen($val) % $block_size);
	return str_pad($val, strlen($val) + $numpad, chr($numpad));
}

#Mailhide related code
function _recaptcha_aes_encrypt($val,$ky)
{
	if(!function_exists('mcrypt_encrypt'))
	{
		throw new Exception('To use reCAPTCHA Mailhide, you need to have the mcrypt php module installed.');
	}
	$mode=MCRYPT_MODE_CBC;   
	$enc=MCRYPT_RIJNDAEL_128;
	$val=_recaptcha_aes_pad($val);
	return mcrypt_encrypt($enc, $ky, $val, $mode, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
}

function _recaptcha_mailhide_urlbase64($x)
{
	return strtr(base64_encode($x), '+/', '-_');
}

#Gets the reCAPTCHA Mailhide url for a given email, public key and private key
function recaptcha_mailhide_url($pubkey, $privkey, $email)
{
	if(empty($pubkey) || empty($privkey))
	{
		throw new Exception('To use reCAPTCHA Mailhide, you have to sign up for a public and private key, you can do so at <a href="http://mailhide.recaptcha.net/apikey">http://mailhide.recaptcha.net/apikey</a>');
	}

	$ky = pack('H*', $privkey);
	$cryptmail = _recaptcha_aes_encrypt($email, $ky);

	return 'http://mailhide.recaptcha.net/d?k='.$pubkey.'&c='._recaptcha_mailhide_urlbase64($cryptmail);
}

#Gets the parts of the email to expose to the user.
#given johndoe@example.com return ["john", "example.com"].
#displayed: john...@example.com
function _recaptcha_mailhide_email_parts($email)
{
	$arr = preg_split('/@/', $email);

	if(strlen($arr[0]) <= 4) {
		$arr[0] = substr($arr[0], 0, 1);
	} elseif(strlen($arr[0]) <= 6) {
		$arr[0] = substr($arr[0], 0, 3);
	} else {
		$arr[0] = substr($arr[0], 0, 4);
	}
	return $arr;
}

#Gets html to display an email address
#to get a key, go to: http://mailhide.recaptcha.net/apikey
function recaptcha_mailhide_html($pubkey, $privkey, $email)
{
	$emailparts = _recaptcha_mailhide_email_parts($email);
	$url = recaptcha_mailhide_url($pubkey, $privkey, $email);

	return htmlentities($emailparts[0]).'<a href="' . htmlentities($url) . '" onclick="window.open(\'' . htmlentities($url) . '\',\'\',\'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300\'); return false" title="Reveal this e-mail address">...</a>@' . htmlentities($emailparts[1]);
}