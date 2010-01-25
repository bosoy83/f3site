<?php
if(iCMS!=1) exit;

#ID wpisu
$id = isset($URL[2]) && is_numeric($URL[2]) ? $URL[2] : 0;

#Szablon
$content->title = $id ? $lang['editPost'] : $lang['sign'];
$content->file = 'posting';

#Skrypty - BBCode
if(isset($cfg['bbcode']))
{
	$content->addScript(LANG_DIR.'edit.js');
	$content->addScript('cache/emots.js');
	$content->addScript('lib/editor.js');
}

#Błędy
$error = array();
$preview = null;

#Nie może postować
if($id && !admit('GB'))
{
	$error[] = $lang['mayNot'];
}
elseif(!$id)
{
	if(empty($cfg['gbPost']))
	{
		$error[] = $lang['disabled'];
	}
	elseif(!UID && $cfg['gbPost']==2)
	{
		$error[] = $lang['mustLogin'];
	}
}

#System CAPTCHA
if(!UID && !empty($cfg['captcha']) && !isset($_SESSION['human']))
{
	require './lib/spam.php';
	$noSPAM = CAPTCHA();
}
else
{
	$noSPAM = false;
}

#Zapisz
if($_POST)
{
	#Dane
	$post = array(
		'who'   => empty($_POST['who']) ? $lang['gall'] : clean($_POST['who'], 40, 1),
		'mail'  => filter_input(0, 'mail', 274), //FILTER_VALIDATE_EMAIL
		'www'   => clean(str_replace(array('javascript:','vbscript:'),'',$_POST['www']), 70, 1),
		'gg'    => (int)$_POST['gg'],
		'icq'   => (int)$_POST['icq'],
		'tlen'  => clean($_POST['tlen'], 30),
		'skype' => clean($_POST['skype'], 32),
		'jabber'=> clean($_POST['jabber'], 80),
		'txt'   => clean($_POST['txt'], 0, 1)
	);

	#Gdy goście nie mogą wstawiać linków + antyspam
	if(!UID)
	{
		if(!isset($cfg['URLs']) && (strpos($post['txt'],'://') OR strpos($post['txt'],'www.')))
		{
			$error[] = $lang['noURL'];
		}
		if($noSPAM)
		{
			if($noSPAM->verify())
			{
				$noSPAM = false;
			}
			else
			{
				$error[] = $lang[$noSPAM->errorId];
			}
		}
	}

	#Strona WWW
	if($post['www'] === 'http://')
	{
		$post['www'] = '';
	}
	elseif(strpos($post['www'], 'http://') !== 0 && strpos($post['www'], 'https://') !== 0)
	{
		$post['www'] = (strpos($post['www'], 'www.') === 0) ? 'http://'.$post['www'] : '';
	}

	#Długość tekstu
	if(empty($post['txt']))
	{
		$error[] = $lang['mustText'];
	}
	elseif(isset($post['txt'][2012]))
	{
		$error[] = $lang['tooLong'];
	}

	#Zapisz
	if(isset($_POST['save']))
	{
		#Blokada czasowa
		if(isset($_SESSION['postTime']) && $_SESSION['postTime'] > $_SERVER['REQUEST_TIME'])
		{
			$error[] = $lang['noFlood'];
		}
		elseif(!empty($cfg['antyFlood']))
		{
			$_SESSION['postTime'] = $_SERVER['REQUEST_TIME'] + $cfg['antyFlood'];
		}

		#Kod z obrazka
		if(!UID && isset($cfg['captcha']) && (empty($_POST['code']) || $_POST['code']!=$_SESSION['code']))
		{
			$error[] = $lang['badCode'];
		}

		#Gdy nie ma błędów, dodaj lub zedytuj wpis
		if(!$error)
		{
			try
			{
				if($id)
				{
					$q = $db->prepare('UPDATE '.PRE.'guestbook SET who=:who, gg=:gg, tlen=:tlen, icq=:icq,
					skype=:skype, jabber=:jabber, mail=:mail, www=:www, txt=:txt WHERE ID=:id');
					$post['id'] = $id;
				}
				else
				{
					$q = $db->prepare('INSERT INTO '.PRE.'guestbook
					(who, UID, lang, date, gg, tlen, icq, skype, jabber, mail, www, ip, txt) VALUES
					(:who, :uid, :lang, :date, :gg, :tlen, :icq, :skype, :jabber, :mail, :www, :ip, :txt)');
					$post['lang'] = LANG;
					$post['date'] = $_SERVER['REQUEST_TIME'];
					$post['ip']  =  $_SERVER['REMOTE_ADDR'];
					$post['uid'] = (UID && $post['who'] === $user['login']) ? UID : 0;
				}
				$q->execute($post);

				#Ustaw blokadę czasową
				$_SESSION['postTime'] = $_SERVER['REQUEST_TIME'];

				#Przekieruj do księgi
				header('Location: '.URL.url('guestbook'));

				#Gdy przekierowanie nie nastąpi
				$content->message($lang['saved']);
			}
			catch(PDOException $e)
			{
				$content->info($lang['error'].$e);
			}
		}
	}

	#Podgląd
	elseif(!$error)
	{
		$preview = nl2br(emots($post['txt']));
		if(isset($cfg['bbcode']))
		{
			include './lib/bbcode.php';
			$preview = BBCode($preview);
		}
	}
}

#Odczyt istniejącego wpisu - FETCH_ASSOC
elseif($id)
{
	if(!$post = $db->query('SELECT * FROM '.PRE.'guestbook WHERE ID='.$id)->fetch(2))
	{
		return;
	}
}

#Nowy wpis
else
{
	$post = array(
		'who'   => UID ? $user['login'] : '',
		'mail'  => '',
		'www'   => 'http://',
		'gg'    => '',
		'icq'   => '',
		'tlen'  => '',
		'skype' => '',
		'jabber'=> '',
		'txt'   => '',
	);
}

#Błędy - zakończ, gdy nie wysłano formularza
if($error)
{
	$content->info('<ul><li>'.join('</li><li>', $error).'</li></ul>');
	if(!$_POST) return 1;
}

#Dane do szablonu
$content->data = array(
	'post'   => &$post,
	'code'   => $noSPAM,
	'rules'  => $cfg['gbRules'],
	'preview'=> $preview,
	'bbcode' => isset($cfg['bbcode'])
);