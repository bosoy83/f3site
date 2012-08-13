<?php /* Register or edit account */
if(iCMS!=1) exit;
require LANG_DIR.'profile.php';
require 'cfg/account.php';

$error = $bad = array();
$photo = '';
$noSPAM = false;
$auto = 0;

#Page title
$content->title = $lang['account'];

#Activation
if(isset($URL[2]) && $URL[1] == 'key')
{
	if(strlen($URL[2])==16 && ctype_alnum($URL[2]))
	{
		$res = $db->query('SELECT UID FROM '.PRE.'tmp WHERE type="ACT" AND KEYID="'.$URL[2].'"');
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

#Registration off
if(!UID)
{
	require_once './lib/spam.php';
	if(empty($cfg['reg']) || isset($cfg['blacklist']) && blacklist($_SERVER['REMOTE_ADDR']))
	{
		$content->info($lang['regoff']); return 1;
	}
	if(!empty($cfg['captcha']) && !isset($_SESSION['human']))
	{
		$noSPAM = CAPTCHA();
	}
}

#Save
if($_POST)
{
	#Too short interval
	if(!isset($_SESSION['formTime']) || $_SESSION['formTime'] > $_SERVER['REQUEST_TIME'])
	{
		$error[] = $lang['isBot'];
	}

	#WWW
	$www = clean($_POST['www'],200);
	$www = str_replace('javascript:','java_script',$www);
	$www = str_replace('vbscript:','vb_script',$www);
	if($www==='http://') $www='';

	#Data, options: 1 show mail, 2 allow comments
	$u = array(
	'gg'  => is_numeric($_POST['gg']) ? $_POST['gg'] : null,
	'icq' => is_numeric($_POST['icq']) ? $_POST['icq'] : null,
	'tlen' => clean($_POST['tlen'],30),
	'www'  => $www,
	'mail' => $_POST['mail'],
	'sex'  => (int)$_POST['sex'],
	'opt'  => isset($_POST['mvis']) | (isset($_POST['comm']) ? 2 : 0),
	'mails' => isset($_POST['mails']),
	'city'  => clean($_POST['city'],30),
	'skype' => clean($_POST['skype'],30),
	'jabber'=> clean($_POST['jabber'],50),
	'about' => clean($_POST['about'],9999,1)
	);

	#About me - too long
	if(isset($u['about'][999])) $error[] = $lang['tooLong'];

	#New user
	if(!UID)
	{
		#Login
		$u['login'] = clean($_POST['login']);
		if(isset($u['login'][31]) || !isset($u['login'][2]))
		{
			$error[] = $lang['badLogin'];
			$bad[] = 'login';
		}
		switch($cfg['logins'])
		{
			case 1: $re = '/^[A-Za-z0-9 _-]*$/'; break;
			case 2: $re = '/^[0-9\pL _.-]*$/u'; break;
			default: $re = '@^[^&/?#=\\\]*$@'; break;
		}
		if(!preg_match($re, $u['login']))
		{
			$error[] = $lang['loginChar'];
			$bad[] = 'login';
		}

		#Login already exists
		$res = $db->query('SELECT COUNT(login) FROM '.PRE.'users WHERE login='.$db->quote($u['login']));
		if($res->fetchColumn() > 0)
		{
			$error[] = $lang['loginEx'];
			$bad[] = 'login';
		}
		$res=null;

		#Banned logins
		if($cfg['nickban'])
		{
			foreach($cfg['nickban'] as $x)
			{
				if(stripos($u['login'],$x)!==false) $error[] = $lang['loginEx'];
			}
			unset($x,$nicks);
		}
		
		#Antispam code
		if($noSPAM)
		{
			if($noSPAM->verify())
			{
				$noSPAM = false;
			}
			else
			{
				$error[] = $lang[$noSPAM->errorId];
				if($cfg['captcha']==1) $bad[] = 'code';
			}
		}
	}

	#Avatar
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

	#Change E-mail or password
	if(UID && $_POST['pass'])
	{
		if(md5($_POST['curPass']) === $user['pass'])
		{
			if(isset($_COOKIE[PRE.'login'])) $auto = 1;
		}
		else
		{
			$error[] = $lang['mustPass'];
			$bad[] = 'curPass';
		}
	}

	#Password
	if(UID && empty($_POST['pass']))
	{
		$u['pass'] = $user['pass'];
	}
	else
	{
		$u['pass'] = md5($_POST['pass']);
		if(strlen($_POST['pass']) < 5 || strlen($_POST['pass']) > 50)
		{
			$error[] = $lang['badPass'];
			$bad[] = 'pass';
		}
		#Passwords different
		if($_POST['pass']!=$_POST['pass2'])
		{
			$error[] = $lang['pass2'];
			$bad[] = 'pass2';
		}
	}

	#E-mail
	if(preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$u['mail']))
	{
		#E-mail already exists
		if(dbCount('users WHERE mail="'.$u['mail'].'"'.(UID?' AND ID!='.UID:'')))
		{
			$error[] = $lang['mailEx'];
			$bad[] = 'mail';
		}
	}
	else
	{
		$u['mail'] = clean($u['mail']);
		$error[] = $lang['badMail'];
		$bad[] = 'mail';
	}

	#Banned E-mail
	if($cfg['mailban'])
	{
		foreach($cfg['mailban'] as $x)
		{
			if(stripos($u['mail'],$x)!==false) $error[] = $lang['mailEx'];
		}
	}

	#Errors
	if($error)
	{
		$content->info(join('<br/><br/>',$error), null, 'error');
		if(UID && !$photo)
		$photo = $db->query('SELECT photo FROM '.PRE.'users WHERE ID='.UID)->fetchColumn();
	}
	else
	{
		try
		{
			if(UID)
			{
				$q = $db->prepare('UPDATE '.PRE.'users SET pass=:pass, mail=:mail, sex=:sex,
				opt=:opt, about=:about, mails=:mails, www=:www, city=:city, icq=:icq,
				skype=:skype, jabber=:jabber, tlen=:tlen, gg=:gg WHERE ID='.UID);
			}
			else
			{
				#Account active if set to AUTO
				$u['lv'] = $cfg['actmeth']==1 ? 1 : 0;
				$u['regt'] = $_SERVER['REQUEST_TIME'];

				$q = $db->prepare('INSERT INTO '.PRE.'users (login,pass,mail,sex,opt,lv,regt,
				about,mails,www,city,icq,skype,jabber,tlen,gg) VALUES (:login,:pass,:mail,
				:sex,:opt,:lv,:regt,:about,:mails,:www,:city,:icq,:skype,:jabber,:tlen,:gg)');
			}

			#Activation link
			if(!UID && $cfg['actmeth']==2)
			{
				#Generate key
				$key = uniqid(mt_rand(100,999));

				#Prepare E-Mail
				include './lib/mail.php';
				$m = new Mailer;
				$m->topic = $lang['mail1'].$u['login'];
				$m->text = file_get_contents(LANG_DIR.'mailReg.php');
				$m->text = str_replace('%link%', URL.url('account/key/'.$key), $m->text);

				#Send e-mail and save the key
				if($m->sendTo($u['login'],$u['mail']))
				{
					$q->execute($u);
					$content->info($lang['byMail'].$u['login'].'<br /><br />'.$lang['noMail']);
					$db->exec('INSERT INTO '.PRE.'tmp VALUES ("'.$key.'",'.$db->lastInsertId().',"ACT")');
					return 1;
				}
				else
				{
					$content->info($lang['mailFail'], 'error');
				}
				unset($m,$key);
			}
			elseif(UID)
			{
				$q->execute($u);
				header('Location: '.URL.url('user/'.urlencode($user['login'])));
			}
			elseif($cfg['actmeth']!=1)
			{
				$q->execute($u); $content->info($lang['noAuto'].$u['login']);
			}
			else
			{
				$q->execute($u); $content->info($lang['auto'].$u['login']);
			}
			#Update user cookie
			if($auto)
			{
				$user['pass'] = $u['pass'];
				setcookie(PRE.'login', UID.':'.$u['pass'], time()+31104000, PATH, null, 0, 1);
			}
			return 1;
		}
		catch(Exception $e)
		{
			$content->info($lang['error'].$e->getMessage(), 'error');
		}
	}
}

#Form
elseif(UID)
{
	$u = $db->query('SELECT * FROM '.PRE.'users WHERE ID='.UID) -> fetch(2);
	$photo = $u['photo'];
}
else
{
	$u = array(
	'login' => isset($_GET['u']) ? clean($_GET['u'],30) : '',
	'mail'  => '',
	'city'  => '',
	'opt'   => 2,
	'sex'   => 1,
	'mails' => 1,
	'gg'    => null,
	'icq'   => null,
	'tlen'  => '',
	'www'   => 'http://',
	'about' => '',
	'skype' => '',
	'jabber'=> '');
}

#Save interval time
$_SESSION['formTime'] = $_SERVER['REQUEST_TIME'] + 5;

#Opcje
$u['mvis'] = $u['opt'] & 1;
$u['comm'] = $u['opt'] & 2;

#Template data
$content->add('account', array(
	'u'     => &$u,
	'width' => $cfg['maxDim1'],
	'height'=> $cfg['maxDim2'],
	'size'  => $cfg['maxSize'],
	'del'   => $photo,
	'bad'   => $bad,
	'code'  => $noSPAM,
	'instr' => $noSPAM && $cfg['captcha']>2 ? $lang['badPet'] : $lang['imgcode'],
	'bbcode'=> isset($cfg['bbcode']),
	'hide'  => empty($_POST['pass']) || !isset($_POST['curPass']),
	'pass'  => isset($_POST['pass']) ? clean($_POST['pass']) : '',
	'pass2' => isset($_POST['pass2']) ? clean($_POST['pass2']) : '',
	'photo' => (UID && isset($cfg['upload'])) ? ($photo ? $photo : 'img/user/0.png') : false
));