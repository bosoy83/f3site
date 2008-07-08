<?php /* Edytuj konto */
if(iCMS!=1) exit;
require LANG_DIR.'profile.php'; #Jêzyk
require 'cfg/account.php'; #Opcje
$error = array();

#Tytu³ strony
$content->title = $lang['account'];

#Aktywacja
if(isset($_GET['keyid']))
{
	if(strlen($_GET['keyid'])==26)
	{
		$res = $db->query('SELECT UID FROM '.PRE.'tmp WHERE type="REG" && KEYID='.$db->quote($_GET['keyid']));
		$id = $res->fetchColumn();
		if(is_numeric($id))
		{
			$db->exec('UPDATE '.PRE.'users SET lv=1 WHERE ID='.$id);
			$content->info($lang['act_ok']);
		}
		else
		{
			$content->info($lang['badKey']);
		}
		unset($id,$res);
	}
	else $content->info($lang['badKey']);
	return;
}

#Rejestracja wy³¹czona?
if($cfg['reg_on']!=1 && LOGD!=1)
{
	$content->info($lang['regoff']); return;
}

#Zapis
if($_POST)
{
	#WWW
	$www = Clean($_POST['www'],200);
	$www = str_replace('javascript:','java_script',$www);
	$www = str_replace('vbscript:','vb_script',$www);
	if($www==='http://') $www='';

	#Dane
	$u = array(
	'gg'  => is_numeric($_POST['gg']) ? (int)$_POST['gg'] : null,
	'icq' => is_numeric($_POST['icq']) ? (int)$_POST['icq'] : null,
	'tlen' => Clean($_POST['tlen'],30),
	'www'  => $www,
	'mail' => $_POST['mail'],
	'mvis'  => isset($_POST['mvis']) ? true : false,
	'mails' => isset($_POST['mails']) ? true : false,
	'city'  => Clean($_POST['city'],30),
	'skype' => Clean($_POST['skype'],30),
	'about' => Clean($_POST['about'],999,true)
	);

	#O sobie - za d³ugi?
	if(isset($about[501])) $error[] = $lang['ab_err'];

	#Niezalogowani
	if(LOGD==2)
	{
		#Login
		$u['login'] = Clean($_POST['login'],30);
		if(isset($u['login'][21]) || !isset($u['login'][2]))
		{
			$error[] = $lang['login_err'];
		}

		#Login istnieje w bazie?
		$res = $db->query('SELECT COUNT(login) FROM '.PRE.'users WHERE login='.$db->quote($u['login']));
		if($res->fetchColumn() > 0)
		{
			$error[] = $lang['login_ex'];
		}
		$res=null;

		#Zabronione loginy
		if($cfg['nickban'])
		{
			foreach($cfg['nickban'] as $x)
			{
				if(strpos($u['login'],$x)!==false) $error[] = $lang['login_ex'];
			}
			unset($x,$nicks);
		}
		
		#Kod
		if($cfg['captcha']==1)
		{
			if($_POST['code']!=$_SESSION['code'] || empty($_SESSION['code']))
			{
				$error[]=$lang['ver_err'];
			}
			$_SESSION['code'] = false;
		}
	}

	#Has³o
	if(LOGD==1 && empty($_POST['pass']))
	{
		$u['pass'] = $user[UID]['pass'];
	}
	else
	{
		$u['pass'] = $_POST['pass'];
		if(!preg_match('/^[a-zA-Z0-9_-]{5,20}$/', $u['pass']))
		{
			$error[] = $lang['pass_err'];
		}
		#Has³a równe?
		elseif($u['pass']!=$_POST['pass2'])
		{
			$error[] = $lang['pass2'];
		}
		$u['pass'] = md5($u['pass']);
	}

	#E-mail
	if(preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$u['mail']))
	{
		#E-mail istnieje w bazie?
		if(db_count('*','users WHERE mail="'.$u['mail'].'"'.((LOGD==1)?' AND ID!='.UID:''))!=0)
		{
			$error[] = $lang['mail_ex'];
		}
	}
	else
	{
		$u['mail'] = Clean($u['mail']); $error[]=$lang['mail_err'];
	}

	#Zabrioniony e-mail?
	if($cfg['mailban'])
	{
		foreach($cfg['mailban'] as $x)
		{
			if(strpos($u['mail'],$x)!==false) $error[]=$lang['mail_ex'];
		}
	}

	#B³êdy?
	if($error)
	{
		$content->info(join('<br /><br />',$error));
	}

	#Zapis
	else
	{
		try
		{
			#Nowy
			if(LOGD!=1)
			{
				#Konto aktywne?
				$u['lv'] = $cfg['actmeth']==1 ? 1 : 0;

				$q = $db->prepare('INSERT INTO '.PRE.'users (login,pass,mail,mvis,lv,regt,
				about,mails,www,city,icq,skype,tlen,gg) VALUES (:login,:pass,:mail,:mvis,:lv,'.
				$_SERVER['REQUEST_TIME'].',:about,:mails,:www,:city,:icq,:skype,:tlen,:gg)');
			}
			#Edycja
			else
			{
				$q = $db->prepare('UPDATE '.PRE.'users SET pass=:pass, mail=:mail, mvis=:mvis,
				about=:about, mails=:mails, www=:www, city=:city, icq=:icq, skype=:skype,
				tlen=:tlen, gg=:gg WHERE ID='.UID);
			}

			#Aktywacja e-mail
			if(LOGD==2 && $cfg['actmeth']==2)
			{
				#Klucz
				$key = uniqid(mt_rand(100,999),1);

				#Przygotuj e-mail
				include './lib/mail.php';
				$m = new Mailer();
				$m ->topic = $lang['u topic'].$u['login'];
				$m ->text = file_get_contents(LANG_DIR.'mail_account.php');
				$m ->text = str_replace('%link%', URL.'?co=account&amp;keyid='.$key, $m->text);

				#Wyœlij i zapisz u¿ytkownika
				if($m->sendTo($u['login'],$u['mail']))
				{
					$q->execute($u);
					$content->info($lang['u bymail'].$u['login'].'<br /><br />'.$lang['u nomail']);
					$db->exec('INSERT INTO '.PRE.'tmp VALUES ("'.$key.'",'.$db->lastInsertId().',"REG")');
					return 1;
				}
				else
				{
					$content->info($lang['u nook']);
				}
				unset($m,$key);
			}
			#Inne
			elseif(LOGD==1)
			{
				$q->execute($u); $content->info($lang['u upd']);
			}
			elseif($cfg['actmeth']!=1)
			{
				$q->execute($u); $content->info($lang['u noauto'].$u['login']);
			}
			else
			{
				$q->execute($u); $content->info($lang['u auto'].$u['login']);
			}
			return 1;
		}
		catch(Exception $e)
		{
			$content->info($lang['u error'].$e);
		}
	}
}

#Form
else
{
	#Odczyt
	if(LOGD==1)
	{
		$u = $db->query('SELECT * FROM '.PRE.'users WHERE ID='.UID) -> fetch(2);
	}
	else
	{
		$u = array('login'=>'','mail'=>'','city'=>'','mvis'=>1,'mails'=>1,'gg'=>null,'icq'=>null,'tlen'=>'','www'=>'http://','about'=>'','skype'=>'');
	}
}

#Dane
$content->data = array(
	'u'    => &$u,
	'code' => $cfg['captcha']==1 && LOGD==2,
);