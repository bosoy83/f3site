<?php #Send E-mails only when really needed!
if(iCMS!=1) exit;
if(!isset($cfg['mailh'])) require './cfg/mail.php';

function sanitize($v)
{
	return str_replace(array("\n","\r",'<','>',';',','), '', $v);
}

class Mailer
{
	public
		$from,
		$mailFrom,
		$topic = '[no topic]',
		$siteTitle,
		$text,
		$url,
		$HTML = 1,
		$exceptions;
	private
		$bcc = array(),
		$header = array(),
		$method = 0,
		$debug = 0, #Aby wy¶wietlaæ komendy, ustaw $debug wy¿ej na 1
		$o;

	#Komenda
	private function cmd($c)
	{
		if(!$this->o) return 0;

		fwrite($this->o, $c."\r\n");
		$reply = fread($this->o, 199);

		if($this->debug)
			echo '&rarr; '.nl2br(htmlspecialchars($c)."\n&larr; ".htmlspecialchars($reply)).'<br />';

		return $reply;
	}

	#Nadawca
	function setSender($name,$mail=false)
	{
		$this->from = sanitize($name);
		if($mail) $this->mailFrom = sanitize($mail);
	}

	#Dodaj nag³ówek
	function addHeader($h)
	{
		$this->header[] = $h."\r\n";
	}

	#Dodaj BCC
	function addBlindCopy($adr)
	{
		$this->bcc[] = sanitize($adr);
	}

	#Wy¶lij
	function sendTo($name,$adr)
	{
		#Z³±cz nag³ówki
		$h = $this->header ? join("\r\n", $this->header) . "\r\n" : '';
		$h.= 'From: ' . $this->from . '<'.$this->mailFrom . ">\r\n";

		#HTML
		if($this->HTML)
		{
			$h.='Content-type: text/html; charset=iso-8859-2'."\r\n";
			$this->text = nl2br($this->text);
		}

		#BBC
		if(count($this->bcc)>0) $h.='Bcc: '.join(',', $this->bcc)."\r\n";

		#Odbiorca
		$adr  = sanitize($adr);
		$name = sanitize($name);

		#Dla SMTP
		if($this->method == 'SMTP')
		{
			#Od
			$this->cmd('MAIL FROM:<'.$this->mailFrom.'>');

			#Do
			$this->cmd('RCPT TO:<'.$adr.'>');
			foreach($this->bcc as $m) $this->cmd('RCPT TO:<'.$m.'>');

			#Dane
			$this->cmd('DATA');

			#Nag³ówki i tekst
			$this->cmd('Subject: '.sanitize($this->topic)."\r\n" . 'To: '.$name."\r\n" . $h."\r\n" . str_replace(
				array('%to%', '%to.email%', '%siteurl%', '%from%'),
				array($name, $adr, '<a href="'.$this->url.'">'.$this->siteTitle.'</a>', $this->from),
				$this->text
			));

			#Wy¶lij (250 = powodzenie)
			$ok = strpos( $this->cmd('.'), '250' ) !== false ? true : false;

			#Reset
			$this->cmd('RSET'); return $ok;
		}

		#Mail()
		elseif($this->method=='MAIL')
		{
			#Wy¶lij
			return mail($name.' <'.$adr.'>', $this->topic, $this->text, $h);
		}
	}

	#Reset BCC i nag³.
	function reset()
	{
		$this->bcc = array();
		$this->header = array();
	}

	#Start
	function __construct()
	{
		global $cfg;
		$this->url = $cfg['adr'];
		$this->siteTitle = $cfg['title'];

		#Po³±czenie SMTP
		if($cfg['mailh']==2 && isset($cfg['mailon']))
		{
			$this->method='SMTP';
			$i=0;

			#Po³±cz (próbuj 3 razy)
			while($i<3 && !$this->o)
			{
				$this->o = fsockopen($cfg['smtp'],$cfg['mailport'],$no,$str,20);
				++$i;
				usleep(500000); #Odczekaj pó³ sekundy
			}
			if(!$this->o) throw new Exception('Cannot send e-mail. Response: '.$str.' ('.$no.')');

			#Hello
			$this->cmd('EHLO '.$cfg['smtp']);

			#Has³o?
			$this->cmd('AUTH LOGIN');
			$this->cmd(base64_encode($cfg['smtpl']));
			$this->cmd(base64_encode($cfg['smtph']));
		}

		#Mail()
		elseif(isset($cfg['mailon'])) $this->method='MAIL';

		#Domy¶lny nadawca
		$this->setSender($cfg['title'], $cfg['mail']);
	}

	#Roz³±cz
	function __destruct()
	{
		if($this->o) { $this->cmd('QUIT'); fclose($this->o); }
	}
}