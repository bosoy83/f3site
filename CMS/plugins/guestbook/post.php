<?php
if(iCMS!=1) exit;

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

#Nie może postować
if($id && !Admit('GB'))
{
	$error[] = $lang['mayNot'];
}
elseif(!$id)
{
	if(!isset($cfg['gbPost']))
	{
		$error[] = $lang['disabled'];
	}
	elseif(!LOGD && !isset($cfg['gbGuest']))
	{
		$error[] = $lang['mustLogin'];
	}
}

#Zapisz
if($_POST)
{
	#Dane
	$post = array(
		'who'   => empty($_POST['who']) ? $lang['gall'] : Clean($_POST['who'], 40, 1),
		'mail'  => filter_input(0, 'mail', 274), //FILTER_VALIDATE_EMAIL
		'www'   => Clean(str_replace(array('javascript:','vbscript:'),'',$_POST['www']), 70, 1),
		'gg'    => (int)$_POST['gg'],
		'icq'   => (int)$_POST['icq'],
		'tlen'  => Clean($_POST['tlen'], 30),
		'skype' => Clean($_POST['skype'], 32),
		'jabber'=> Clean($_POST['jabber'], 80),
		'txt'   => Clean($_POST['txt'], 0, 1)
	);

	#Blokada czasowa
	if(isset($_SESSION['postTime']) && $_SESSION['postTime'] > $_SERVER['REQUEST_TIME'])
	{
		$error[] = $lang['noFlood'];
	}
	elseif(!empty($cfg['antyFlood']))
	{
		$_SESSION['gbTime'] = $_SERVER['REQUEST_TIME'] + $cfg['antyFlood'];
	}
	#Kod z obrazka
	if(!LOGD && isset($cfg['captcha']) && (empty($_POST['code']) || $_POST['code']!=$_SESSION['code']))
	{
		$error[] = $lang['badCode'];
	}
	#Linki
	if(!isset($cfg['URLs']))
	{
		if(strpos($post['txt'],'://') OR strpos($post['txt'],'www.'))
		{
			$error[] = $lang['noURL'];
		}
	}
	#Strona WWW
	if($post['www'] === 'http://')
	{
		$post['www'] = '';
	}
	elseif(strpos($post['www'], 'http://') !== 0)
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
	if(isset($_POST['save']) && !$error)
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
				$post['lang'] = $nlang;
				$post['date'] = $_SERVER['REQUEST_TIME'];
				$post['ip']  =  $_SERVER['REMOTE_ADDR'];
				$post['uid'] = (LOGD && $post['who'] === $user[UID]['login']) ? UID : 0;
			}
			$q->execute($post);

			#Ustaw blokadę czasową
			$_SESSION['postTime'] = $_SERVER['REQUEST_TIME'];

			#Przekieruj do księgi
			header('Location: '.URL.'?co=guestbook');

			#Gdy przekierowanie nie nastąpi
			$content->message($lang['saved']);
		}
		catch(PDOException $e)
		{
			$content->info($lang['error'].$e);
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
		'who'   => LOGD ? $user[UID]['login'] : '',
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
	'rules'  => $cfg['gbRules'],
	'bbcode' => isset($cfg['bbcode'])
);