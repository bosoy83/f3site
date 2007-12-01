<?php
if(iCMS!=1) exit;
require($catl.'profile.php'); #Jêzyk
require('cfg/account.php'); #Opcje
$error=array();

#Aktywacja
if(isset($_GET['keyid']))
{
	if(strlen($_GET['keyid'])==26)
	{
		$res=$db->query('SELECT UID FROM '.PRE.'tmp WHERE type="REG" && KEYID='.$db->quote($_GET['keyid']));
		$id=$res->fetchColumn();
		if(is_numeric($id))
		{
			$db->exec('UPDATE '.PRE.'users SET lv=1 WHERE ID='.$id);
			Info($lang['act_ok']);
		}
		else
		{
			Info($lang['badkey']);
		}
		unset($id,$res);
	}
	else Info($lang['badkey']);
	return;
}

#Rejestracja wy³¹czona?
if($cfg['reg_on']!=1 && LOGD!=1)
{
	Info($lang['regoff']);
}

#Zapis
if($_POST)
{
	#WWW
	$x_w=Clean($_POST['x_w'],200);
	$x_w=str_replace('javascript:','java_script',$x_w);
	$x_w=str_replace('vbscript:','vb_script',$x_w);
	if($x_w==='http://') $x_w='';

	#Dane
	$u=array(
	'gg'  =>is_numeric($_POST['x_gg'])?$_POST['x_gg']:'',
	'tlen'=>Clean($_POST['x_tl'],30),
	'icq' =>is_numeric($_POST['x_icq'])?$_POST['x_icq']:'',
	'www' =>$x_w,
	'mvis'=>isset($_POST['x_mvis'])?1:0,
	'mails'=>isset($_POST['x_mails'])?1:0,
	'city'=>Clean($_POST['x_city'],30),
	'skype'=>Clean($_POST['x_sk'],30),
	'about'=>Clean($_POST['x_ab'],999)
	);

	#O sobie - za d³ugi?
	if(isset($x_ab[501])) $error[]=$lang['ab_err'];

	#Niezalogowani
	if(LOGD==2)
	{
		#Login
		$u['login']=Clean($_POST['x_l'],30);
		if(isset($u['login'][21]) || !isset($u['login'][2]))
		{
			$error[]=$lang['login_err'];
		}

		#Login istnieje w bazie?
		$res=$db->query('SELECT COUNT(login) FROM '.PRE.'users WHERE login='.$db->quote($u['login']));
		if($res->fetchColumn() > 0)
		{
			$error[]=$lang['login_ex'];
		}
		$res=null;

		#Zabronione loginy
		if($cfg['nickban'])
		{
			foreach($cfg['nickban'] as $n)
			{
				if(strpos($u['login'],$n)!==false) $error[]=$lang['login_ex'];
			}
			unset($n,$nicks);
		}
		
		#Kod
		if($cfg['imgsec']==1)
		{
			if($_POST['x_code']!=$_SESSION['code'] || empty($_SESSION['code']))
			{
				$error[]=$lang['ver_err'];
			}
			$_SESSION['code']=false;
		}
	}

	#Has³o
	if(LOGD==1 && empty($_POST['x_p']))
	{
		$u['pass']=$user[UID]['pass'];
	}
	else
	{
		$u['pass']=$_POST['x_p'];
		if(!ereg('^[a-zA-Z0-9_-]{5,20}$',$u['pass']))
		{
			$error[]=$lang['pass_err'];
		}
		#Has³a równe?
		if($u['pass']!=$_POST['x_p2'])
		{
			$error[]=$lang['pass2'];
		}
		$u['pass']=md5($u['pass']);
	}

	#E-mail	
	$u['mail']=$_POST['x_m'];
	if(preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$u['mail']))
	{
		#E-mail istnieje w bazie?
		if(db_count('*','users',' WHERE mail="'.$u['mail'].'"'.((LOGD==1)?' && ID!='.UID:''))!=0)
		{
			$error[]=$lang['mail_ex'];
		}
	}
	else
	{
		$u['mail']=Clean($u['mail']); $error[]=$lang['mail_err'];
	}

	#Zabrioniony e-mail?
	if($cfg['mailban'])
	{
		if(in_array(substr(strstr($u['mail'],'@'),1),$cfg['mailban'])) $error[]=$lang['mail_ex'];
	}

	#B³êdy?
	if($error)
	{
		Info(join('<br /><br />',$error));
	}

	#Zapis
	else
	{
		#Konto aktywne?
		$u['lv']=($cfg['actmeth']==1)?1:0;

		#Nowy
		if(LOGD!=1)
		{
			$q=$db->prepare('INSERT INTO '.PRE.'users (login,pass,mail,mvis,gid,lv,
			regt,about,mails,www,city,icq,skype,tlen,gg) VALUES (:login,:pass,
			:mail,:mvis,1,:lv,"'.NOW.'",:about,:mails,:www,:city,:icq,:skype,:tlen,:gg)');
		}

		#Edycja
		else
		{
			$q=$db->prepare('UPDATE '.PRE.'users SET pass=:pass, mail=:mail, mvis=:mvis,
			about=:about, mails=:mails, www=:www, city=:city, icq=:icq, skype=:skype,
			tlen=:tlen, gg=:gg WHERE ID='.UID);
		}

		#Aktywacja e-mail
		if(LOGD==2 && $cfg['actmeth']==2)
		{
			#Klucz
			$key=uniqid(mt_rand(100,999),1);

			#Przygotuj e-mail
			include('./lib/mail.php');
			$m=new Mailer();
			$m->topic=$lang['u topic'].$x_l;
			$m->text=file_get_contents($catl.'mail_account.php');
			$m->text=str_replace('%link%', URL.'?co=account&amp;keyid='.$key, $m->text);

			#Wyœlij i zapisz u¿ytkownika
			if(SendTo($x_l,$x_m))
			{
				if($q->execute($u))
				{
					Info($lang['u bymail'].$x_l.'<br /><br />'.$lang['u nomail']);
					$db->exec('INSERT INTO '.PRE.'tmp VALUES ("'.$key.'",'.$db->lastInsertId().',"REG")');
				}
			}
			else
			{
				Info($lang['u nook']);
			}
			unset($m,$key);
		}
		#Inne
		elseif($q->execute($u))
		{
			if(LOGD==1) Info($lang['u upd']);
			elseif($cfg['actmeth']!=1) Info($lang['u noauto'].$u['login']);
			else Info($lang['u auto'].$u['login']);
			return;
		}
		else { Info($lang['u error']); }
	}
}

#Form
else
{
	#Odczyt
	if(LOGD==1)
	{
		$res=$db->query('SELECT * FROM '.PRE.'users WHERE ID='.UID);
		$u=$res->fetch(2);
		$res=null;
	}
	else
	{
		$u=array('login'=>'','mail'=>'','city'=>'','mvis'=>1,'mails'=>1,'gg'=>null,'icq'=>null,'tlen'=>'','www'=>'http://','about'=>'','skype'=>'');
	}
}

#Szablon
include($catst.'account.php');
unset($u);
?>
