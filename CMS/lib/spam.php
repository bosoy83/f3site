<?php
#This is a bridge to external CAPTCHA systems
#Call CAPTCHA() or select proper class

#Return proper class
function CAPTCHA($cfg = null)
{
	if(!$cfg) global $cfg;
	if(empty($cfg['captcha']) || isset($_SESSION['human'])) return false;

	switch($cfg['captcha'])
	{
		case 3:
			return new Asirra;
		case 2:
			$x = new reCAPTCHA;
			$x->pubKey = $cfg['pubKey'];
			$x->prvKey = $cfg['prvKey'];
			return $x;
		default:
			return new ImgCode;
	}
}

#Check IP in blacklist
function blacklist($ip)
{
	static $list;
	if(!$list && file_exists('cfg/blacklist.txt'))
	{
		$list = file_get_contents('cfg/blacklist.txt');
	}
	return strpos($list,"\n".$ip."\n")>0;
}

#reCAPTCHA™ - recaptcha.net - get your own keys there
class reCAPTCHA
{
	public
		$pubKey,
		$prvKey;
	protected
		$error;

	function verify()
	{
		if(empty($this->pubKey))
		{
			throw new Exception('API key not specified in admin panel!');
		}
		$out = request('api-verify.recaptcha.net', '/verify', 'POST', array(
			'privatekey' => $this->prvKey,
			'remoteip'   => $_SERVER['REMOTE_ADDR'],
			'challenge'  => $_POST['recaptcha_challenge_field'],
			'response'   => $_POST['recaptcha_response_field']
		),1);

		#First line must be TRUE
		if($out[0] == 'true')
		{
			$_SESSION['human'] = 1;
			return 1;
		}
		$this->error = $out[1];
		$this->errorId = 'badCode';
		return 0;
	}
	function __toString()
	{
		if(empty($this->pubKey))
		{
			return 'Set API key in admin panel!';
		}
		return '<script type="text/javascript" id="RCS">
		include("http://api.recaptcha.net/js/recaptcha_ajax.js",function(){
		Recaptcha.create("'.$this->pubKey.'",$("RCS").parentNode,{lang:"'.LANG.'",error:"'.$this->error.'"})})
		</script>';
	}
}

#Asirra - asirra.com
class Asirra
{
	function verify()
	{
		$name = urlencode($_POST['Asirra_Ticket']);
		$out = request('challenge.asirra.com', '/cgi/Asirra?action=ValidateTicket&ticket='.$name);
		$xml = simplexml_load_string($out);
		if($xml->Result == 'Pass')
		{
			$_SESSION['human'] = 1;
			return 1;
		}
		$this->errorId = 'badPet';
	}
	function __toString()
	{
		return '<script type="text/javascript" src="http://challenge.asirra.com/js/AsirraClientSide.js"></script>
		<script type="text/javascript">
		ASI = $("asirra_InstructionsTextId")
		ASF = $("Asirra_Ticket").form
		ASE = window.ASBad || "Select only cats!"
		if(window.ASText) ASI.innerHTML = ASText
		addEvent("submit", function(e) {
			Asirra_CheckIfHuman(function(x) { if(x) ASF.submit(); else alert(ASE) })
			if(e.preventDefault) e.preventDefault(); return false}, ASF)
		</script>';
	}
}

#Lightweight solution - digits on image
class ImgCode
{
	function verify()
	{
		if(empty($_POST['code']) || $_POST['code']!=$_SESSION['code'])
		{
			$this->errorId = 'badCode';
			return 0;
		}
		$_SESSION['human'] = 1;
		return 1;
	}
	function __toString()
	{
		return '<img src="code.php" style="border: 1px solid gray; vertical-align: top" />
		<input name="code" style="height: 21px" />';
	}
}

#Connect to server
function request($host,$path,$method='GET',$post=null,$asArray=false)
{
	#POST data
	if(is_array($post))
	{
		$list = array();
		foreach($post as $key=>$val)
		{
			$list[] = sprintf('%s=%s', $key, urlencode($val));
		}
		$post = join('&', $list);
		$len  = strlen($post);
	}
	else
	{
		$post = '';
		$len  = '0';
	}
	$list = array();
	$now = 0;

	$in  = "$method $path HTTP/1.0\r\n";
	$in .= "Host: $host\r\n";
	$in .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$in .= "Content-Length: $len\r\n";
	$in .= "User-Agent: PHP\r\n";
	$in .= "Connection: Close\r\n\r\n$post";

	#Open connection and return answer
	if(!$res = fsockopen($host, 80, $e, $err, 20))
	{
		throw new Exception('Cannot connect to server: '.$err);
	}
	fwrite($res, $in);
	while(!feof($res))
	{
		if($now)
		{
			$list[] = trim(fgets($res));
		}
		elseif(fgets($res) == "\r\n")
		{
			$now = 1;
		}
	}
	return $asArray ? $list : join('',$list);
}