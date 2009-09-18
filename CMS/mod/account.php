<?php /* Edytuj konto */
if(iCMS!=1) exit;
require LANG_DIR.'profile.php'; #Jêzyk
require 'cfg/account.php'; #Opcje

$error = $bad = array();
$photo = '';

#Tytu³ strony
$content->title = $lang['account'];

#Aktywacja
if(isset($_GET['keyid']))
{
	if(strlen($_GET['keyid'])==16 && ctype_alnum($_GET['keyid']))
	{
		$res = $db->query('SELECT UID FROM '.PRE.'tmp WHERE type="ACT" AND KEYID="'.$_GET['keyid'].'"');
		$id = $res->fetchColumn();
		if(is_numeric($id))
		{
			$db->exec('UPDATE '.PRE.'users SET lv=1 WHERE ID='.$id);
			$db->exec('DELETE FROM '.PRE.'tmp WHERE type="ACT" AND UID='.$id);
			$content->info($lang['act']);
		}
		else
		{
			$content->info($lang['badKey']);
		}
		unset($id,$res);
	}
	else $content->info($lang['badKey']);
	return 1;
}

#Rejestracja wy³¹czona?
if(!isset($cfg['reg']) && !LOGD)
{
	$content->info($lang['regoff']); return 1;
}

#Zapis
if($_POST)
{
	#WWW
	$www = Clean($_POST['www'],200);
	$www = str_replace('javascript:','java_script',$www);
	$www = str_replace('vbscript:','vb_script',$www);
	if($www==='http://') $www='';

	#Dane + opcje - 1: pokazuj mail, 2: pozwól komentowaæ
	$u = array(
	'gg'  => is_numeric($_POST['gg']) ? (int)$_POST['gg'] : null,
	'icq' => is_numeric($_POST['icq']) ? (int)$_POST['icq'] : null,
	'tlen' => Clean($_POST['tlen'],30),
	'www'  => $www,
	'mail' => $_POST['mail'],
	'opt'  => isset($_POST['mvis']) | (isset($_POST['comm']) ? 2 : 0),
	'mails' => isset($_POST['mails']),
	'city'  => Clean($_POST['city'],30),
	'skype' => Clean($_POST['skype'],30),
	'jabber'=> Clean($_POST['jabber'],50),
	'about' => Clean($_POST['about'],9999,1)
	);

	#O sobie - za d³ugi?
	if(isset($u['about'][999])) $error[] = $lang['tooLong'];

	#Niezalogowani
	if(!LOGD)
	{
		#Login
		$u['login'] = Clean($_POST['login'],30);
		if(isset($u['login'][31]) || !isset($u['login'][2]))
		{
			$error[] = $lang['badLogin'];
			$bad[] = 'login';
		}

		#Login istnieje w bazie?
		$res = $db->query('SELECT COUNT(login) FROM '.PRE.'users WHERE login='.$db->quote($u['login']));
		if($res->fetchColumn() > 0)
		{
			$error[] = $lang['loginEx'];
			$bad[] = 'login';
		}
		$res=null;

		#Zabronione loginy
		if($cfg['nickban'])
		{
			foreach($cfg['nickban'] as $x)
			{
				if(stripos($u['login'],$x)!==false) $error[] = $lang['loginEx'];
			}
			unset($x,$nicks);
		}
		
		#Kod
		if(isset($cfg['captcha']))
		{
			if($_POST['code']!=$_SESSION['code'] || empty($_SESSION['code']))
			{
				$error[] = $lang['badCode'];
				$bad[] = 'code';
			}
			$_SESSION['code'] = false;
		}
	}

	#Awatar
	elseif(isset($cfg['upload']) && $_FILES['photo']['name'])
	{
		require './lib/avatar.php';
		$photo = Avatar($error);
	}
	elseif(isset($_POST['del']))
	{
		require './lib/avatar.php';
		$photo = RemoveAvatar($error);
	}

	#Zmiana has³a lub E-mail
	if(LOGD && $_POST['pass'] && $_POST['curPass'] != $user[UID]['pass'])
	{
		$error[] = $lang['mustPass'];
		$bad[] = 'curPass';
	}

	#Has³o
	if(LOGD && empty($_POST['pass']))
	{
		$u['pass'] = $user[UID]['pass'];
	}
	else
	{
		$u['pass'] = $_POST['pass'];
		if(!preg_match('/^[a-zA-Z0-9_-]{5,20}$/', $u['pass']))
		{
			$error[] = $lang['badPass'];
			$bad[] = 'pass';
		}
		#Has³a równe?
		elseif($u['pass']!=$_POST['pass2'])
		{
			$error[] = $lang['pass2'];
			$bad[] = 'pass2';
		}
		$u['pass'] = md5($u['pass']);
	}

	#E-mail
	if(preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$u['mail']))
	{
		#E-mail istnieje w bazie?
		if(db_count('users WHERE mail="'.$u['mail'].'"'.((LOGD)?' AND ID!='.UID:'')) != 0)
		{
			$error[] = $lang['mailEx'];
			$bad[] = 'mail';
		}
	}
	else
	{
		$u['mail'] = Clean($u['mail']);
		$error[] = $lang['badMail'];
		$bad[] = 'mail';
	}

	#Zabrioniony e-mail?
	if($cfg['mailban'])
	{
		foreach($cfg['mailban'] as $x)
		{
			if(stripos($u['mail'],$x)!==false) $error[] = $lang['mailEx'];
		}
	}

	#B³êdy?
	if($error)
	{
		$content->info(join('<br /><br />',$error));
		if(LOGD && !$photo)
		$photo = $db->query('SELECT photo FROM '.PRE.'users WHERE ID='.UID) -> fetchColumn();
	}

	#Zapis
	else
	{
		try
		{
			#Edytuj
			if(LOGD)
			{
				$q = $db->prepare('UPDATE '.PRE.'users SET pass=:pass, mail=:mail, opt=:opt,
				about=:about, mails=:mails, www=:www, city=:city, icq=:icq, skype=:skype,
				jabber=:jabber, tlen=:tlen, gg=:gg WHERE ID='.UID);
			}
			#Nowy
			else
			{
				#Konto aktywne?
				$u['lv'] = $cfg['actmeth']==1 ? 1 : 0;
				$u['regt'] = $_SERVER['REQUEST_TIME'];

				$q = $db->prepare('INSERT INTO '.PRE.'users (login,pass,mail,opt,lv,regt,
				about,mails,www,city,icq,skype,jabber,tlen,gg) VALUES (:login,:pass,:mail,
				:opt,:lv,:regt,:about,:mails,:www,:city,:icq,:skype,:jabber,:tlen,:gg)');
			}

			#Aktywacja e-mail
			if(!LOGD && $cfg['actmeth']==2)
			{
				#Klucz
				$key = uniqid(mt_rand(100,999));

				#Przygotuj e-mail
				include './lib/mail.php';
				$m = new Mailer;
				$m->topic = $lang['mail1'].$u['login'];
				$m->text = file_get_contents(LANG_DIR.'mailReg.php');
				$m->text = str_replace('%link%', URL.'?co=account&amp;keyid='.$key, $m->text);

				#Wyœlij i zapisz u¿ytkownika
				if($m->sendTo($u['login'],$u['mail']))
				{
					$q->execute($u);
					$content->info($lang['byMail'].$u['login'].'<br /><br />'.$lang['noMail']);
					$db->exec('INSERT INTO '.PRE.'tmp VALUES ("'.$key.'",'.$db->lastInsertId().',"ACT")');
					return 1;
				}
				else
				{
					$content->info($lang['mailFail']);
				}
				unset($m,$key);
			}
			#Inne
			elseif(LOGD)
			{
				$q->execute($u);
				header('Location: '.URL.'?co=user&id='.UID);
			}
			elseif($cfg['actmeth']!=1)
			{
				$q->execute($u); $content->info($lang['noAuto'].$u['login']);
			}
			else
			{
				$q->execute($u); $content->info($lang['auto'].$u['login']);
			}
			return 1;
		}
		catch(Exception $e)
		{
			$content->info($lang['error'].$e->getMessage());
		}
	}
}

#Form
else
{
	#Odczyt
	if(LOGD)
	{
		$u = $db->query('SELECT * FROM '.PRE.'users WHERE ID='.UID) -> fetch(2);
		$photo = $u['photo'];
	}
	else
	{
		$u = array(
		'login' => '',
		'mail'  => '',
		'city'  => '',
		'opt'   => 3,
		'mails' => 1,
		'gg'    => null,
		'icq'   => null,
		'tlen'  => '',
		'www'   => 'http://',
		'about' => '',
		'skype' => '',
		'jabber'=> '');
	}
}

#Opcje
$u['mvis'] = $u['opt'] & 1;
$u['comm'] = $u['opt'] & 2;

#Dane
$content->data = array(
	'u'     => &$u,
	'width' => $cfg['maxDim1'],
	'height'=> $cfg['maxDim2'],
	'size'  => $cfg['maxSize'],
	'del'   => $photo,
	'bad'   => $bad,
	'bbcode'=> isset($cfg['aboutBBC']),
	'code'  => isset($cfg['captcha']) && !LOGD,
	'photo' => (LOGD && isset($cfg['upload'])) ? ($photo ? $photo : 'img/user/0.jpg') : false
);