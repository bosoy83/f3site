<?php
#Send E-mails only when really needed!
if(iCMS!=1) exit;
if(!isset($cfg['mailon'])) require('./cfg/mail.php');

function mail_rep($v)
{
	$v=str_replace("\n",'',$v);
	$v=str_replace("\r",'',$v);
	return str_replace(array('<','>',';',','),'',$v);
}

class Mailer
{
	public
		$from,
		$from_adr,
		$topic='[no topic]',
		$text,
		$url,
		$HTML=1;
	private
		$bcc=array(),
		$header=array(),
		$method=0,
		$debug=0,
		$o=null; //Aby wy¶wietlaæ komendy, ustaw $debug wy¿ej na 1

	#Komenda
	private function Cmd($c)
	{
		if(!$this->o) return 0;

		fwrite($this->o, $c."\r\n");
		$reply = fread($this->o, 199);

		if($this->debug)
			echo '&rarr; '.nl2br(htmlspecialchars($c)).
			'<br />&larr; '.nl2br(htmlspecialchars($reply)).'<br />';

		return $reply;
	}

	#Nadawca
	function SetSender($name,$mail=false)
	{
		$this->from = mail_rep($name);
		if($mail) $this->from_adr = mail_rep($mail);
	}

	#Dodaj nag³ówek
	function AddHeader($h)
	{
		$this->header[] = $h."\r\n";
	}

	#Dodaj BCC
	function AddBlindCopy($adr)
	{
		$this->bcc[] = mail_rep($adr);
	}

	#Wy¶lij
	function SendTo($name,$adr)
	{
		//Z³±cz nag³ówki
		$h = (($this->header)?join("\r\n", $this->header)."\r\n":'')
			.'From: '.$this->from . '<'.$this->from_adr.">\r\n";

		//HTML?
		if($this->HTML) $h.='Content-type: text/html; charset=iso-8859-2'."\r\n";

		//BCC
		if(count($this->bcc)>0) $h.='Bcc: '.join(',', $this->bcc)."\r\n";

		//Odbiorca
		$adr=mail_rep($adr);
		$name=mail_rep($name);

		//Dla SMTP
		if($this->method=='SMTP')
		{
			//Od
			$this->Cmd('MAIL FROM:<'.$this->from_adr.'>');

			//Do
			$this->Cmd('RCPT TO:<'.$adr.'>');
			foreach($this->bcc as $m) $this->Cmd('RCPT TO:<'.$m.'>');

			//Dane
			$this->Cmd('DATA');

			//Nag³ówki i tekst
			$this->Cmd(
			'Subject: '.mail_rep($this->topic)."\r\n".
			'To: '.$name."\r\n"
			.$h."\r\n"
			.str_replace( array('%to%','%to.email%','%siteurl%','%from%'), array($name, $adr, '<a href="'.$this->url.'">'.$this->url.'</a>', $this->from), $this->text ));

			//Wy¶lij (250 = powodzenie)
			$ok = strpos( $this->Cmd('.'), '250' ) !== false ? 1 : 0;

			//Reset
			$this->Cmd('RSET'); return (($ok)?true:false);
		}

		//Mail()
		elseif($this->method=='MAIL')
		{
			//Wy¶lij
			return mail($name.' <'.$adr.'>', $this->topic, $this->text, $h);
		}
	}

	#Reset BCC i nag³.
	function Reset()
	{
		$this->bcc=array();
		$this->header=array();
	}

	#Start
	function __construct()
	{
		global $cfg;
		$this->url=$cfg['adr'];

		//Po³±czenie SMTP
		if($cfg['mailh']==2 && $cfg['mailon']==1)
		{
			$this->method='SMTP';
			$i=0;

			//Po³±cz (próbuj 3 razy)
			while($i<3 && !$this->o)
			{
				$this->o = fsockopen($cfg['smtp'],$cfg['mailport'],$no,$str,20);
				++$i;
			}
			if(!$this->o) echo 'ERROR: Cannot send e-mail :: '.$str.' ('.$no.')';

			//Hello
			$this->Cmd('EHLO '.$cfg['smtp']);

			//Has³o?
			$this->Cmd('AUTH LOGIN');
			$this->Cmd(base64_encode($cfg['smtpl']));
			$this->Cmd(base64_encode($cfg['smtph']));
		}

		//Mail()
		elseif($cfg['mailon']==1) { $this->method='MAIL'; }

		//Domy¶lny nadawca
		$this->setSender($cfg['title'], $cfg['mail']);
	}

	#Roz³±cz
	function __destruct()
	{
		$this->Cmd('QUIT');
		if($this->o) fclose($this->o);
	}
}
?>