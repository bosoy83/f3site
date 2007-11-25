<?php
if(iCMS!=1) exit;
require($catl.'profile.php'); #Jêzyk
require('cfg/account.php'); #Opcje
$error=array();

#Rejestracja wy³¹czona?
if($cfg['reg_on']!=1 && LOGD!=1)
{
	Info($lang['regoff']);
}

#Aktywacja
if(isset($_GET['keyid']))
{
	if(strlen($_GET['keyid'])==26)
	{
		$res=$db->query('SELECT UID FROM '.PRE.'tmp WHERE type="REG" && KEYID="'.$db->quote($_GET['keyid']).'"');
		$id=$res->fetchColumn();
		if(is_numeric($uid))
		{
			$db->exec('UPDATE '.PRE.'users SET lv=1 WHERE ID='.$id);
		}
		else
		{
			Info($lang['u_badkey']); return;
		}
		unset($id,$res);
	}
	else { Info($lang['u_badkey']); return; }
}

#Zapis
elseif($_POST)
{
	#Niezalogowani
	if(LOGD==2)
	{
		#Login
		$xu_l=Clean($_POST['xu_l'],30);
		if(strlen($xu_l)>20 || strlen($xu_l)<3)
		{
			$error[]=$lang['u loginerr'];
		}

		#Login istnieje w bazie?
		$res=$db->query('SELECT COUNT(login) FROM '.PRE.'users WHERE login="'.$db->quote($xu_l).'"');
		if($res->fetchColumn() > 0)
		{
			$error[]=$lang['u loginex'];
		}
		$res=null;

		#Zabronione loginy
		if(count($cfg['nickban'])>0)
		{
			$nicks=explode("\n",$cfg['nickban']);
			foreach($nicks as $n)
			{
				if(strpos($nicks,$n)!==false) $error[]=$lang['u nickban'];
			}
			unset($n,$nicks);
		}
		
		#Kod
		if($cfg['imgsec']==1)
		{
			if($_POST['xu_code']!=$_SESSION['code'] || empty($_SESSION['code']))
			{
				$error[]=$lang['epbcode'];
			}
			$_SESSION['code']=false;
		}
	}

	#Has³o
	if(empty($_POST['xu_p']) && LOGD==1)
	{
		$xu_p=$user[UID]['pass'];
	}
	else
	{
		$xu_p=$_POST['xu_p'];
		if(!ereg('^[a-zA-Z0-9_-]{5,20}$',$xu_p))
		{
			$error[]=$lang['eperrp'];
		}
		if($xu_p!=$_POST['xu_p2'])
		{
			$error[]=$lang['eperrp2'];
		}
		$xu_p=md5($xu_p);
	}

	#E-mail
	if(preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$_POST['xu_m']))
	{
		$xu_m=$_POST['xu_m'];

		#E-mail istnieje w bazie?
		if(db_count('*','users',' WHERE mail="'.$xu_m.'"'.((LOGD==1)?' && ID!='.UID:''))!=0)
		{
			$error[]=$lang['eperrm'];
		}
	}
	else
	{
		$xu_m=$user[UID]['mail'];
	}
	
	#Zabrioniony e-mail?
	if($xu_m!='' && count($cfg['mailban'])>0)
	{
		if(in_array(substr(strstr($xu_m,'@'),1),$cfg['mailban'])) $error[]=$lang['u mailban'];
	}

	#O sobie
	$xu_ab=Clean($_POST['xu_ab']);
	if(isset($xu_ab[501])) $error[]=$lang['eperrab'];

	#Sk¹d
	$xu_fr=Clean($_POST['xu_fr']);

	#Komunikatory
	$xu_gg=(is_numeric($_POST['xu_gg']))?$_POST['xu_gg']:'';
	$xu_icq=(is_numeric($_POST['xu_icq']))?$_POST['xu_icq']:'';
	$xu_tl=Clean($_POST['xu_tl'],30);
	$xu_sk=Clean($_POST['xu_sk'],30);

	#WWW
	$xu_w=Clean($_POST['xu_w'],200);
	$xu_w=str_replace('javascript:','java_script',$xu_w);
	$xu_w=str_replace('vbscript:','vb_script',$xu_w);

	#Inne
	if(isset($xu_m[71])) exit('E-mail too long!');
	$xu_mvis=isset($_POST['xu_mvis'])?1:0;
	$xu_mails=isset($_POST['xu_mails'])?1:0;

	#B³êdy?
	if($error)
	{
		Info(join('<br /><br />',$error));
	}

	#Zapis
	else
	{
		#Nowy
		if(LOGD!=1)
		{
			$q=$db->prepare('INSERT INTO '.PRE.'users (login,pass,mail,mvis,gid,lv,
			regt,about,mails,www,city,icq,skype,tlen,gg) VALUES (:login,:pass,
			:mail,:mvis,1,:lv,:date,:about,:mails,:www,:city,:icq,:skype,:tlen,:gg)');

			$q->bindValue(':login',$xu_l);
			$q->bindValue(':date',NOW);
			$q->bindValue(':lv', (($cfg['actmeth']=='auto')?1:0) );
		}

		#Edycja
		else
		{
			$q=$db->prepare('UPDATE '.PRE.'users SET pass=:pass, mail=:mail, mvis=:mvis,
			about=:about, mails=:mails, www=:www, city=:city, icq=:icq, skype=:skype,
			tlen=:tlen, gg=:gg WHERE ID='.UID);
		}

		#Parametry
		$q->bindValue(':pass',$xu_p);
		$q->bindValue(':mail',$xu_m);
		$q->bindValue(':mvis',$xu_mvis,1); //INT
		$q->bindParam(':about',$xu_ab);
		$q->bindValue(':mails',$xu_mails,1); //INT
		$q->bindValue(':www',$xu_w);
		$q->bindValue(':city',$xu_fr);
		$q->bindValue(':icq',$xu_icq);
		$q->bindValue(':skype',$xu_sk);
		$q->bindValue(':tlen',$xu_tl);
		$q->bindValue(':gg',$xu_gg);

		#Aktywacja e-mail
		if(LOGD==2 && $cfg['actmeth']=='mail')
		{
			#Klucz
			$key=uniqid(mt_rand(100,999),1);

			#Przygotuj e-mail
			include('./lib/mail.php');
			$m=new Mailer();
			$m->topic=$lang['u topic'].$xu_l;
			$m->text=file_get_contents($catl.'mail_account.php');
			$m->text=str_replace('%link%', URL.'?co=account&amp;keyid='.$key, $m->text);

			#Wyœlij i zapisz u¿ytkownika
			if(SendTo($xu_l,$xu_m))
			{
				if($q->execute())
				{
					Info($lang['u bymail'].$xu_l.'<br /><br />'.$lang['u nomail']);
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
		elseif($q->execute())
		{
			if(LOGD==1) { Info($lang['u upd']); }
			elseif($cfg['actmeth']!='auto') { Info($lang['u noauto'].$xu_l); }
			else { Info($lang['u auto'].$xu_l); }
		}
		else { Info($lang['u error']); $error[]=1; }
	}
}

#Form
if(!$_POST || $error)
{
	if(LOGD==1 && !$error)
	{
		#Odczyt
		$res=$db->query('SELECT * FROM '.PRE.'users WHERE ID='.UID);
		$user[UID]=$res->fetch(2);
		$res=null;
	}

	#Dane dla FORM
	if($_POST)
	{
		$data=array(
		'mail'=>$xu_m,	'mvis'=>(($xu_mvis)?'checked="checked"':''),
		'city'=>$xu_fr,	'mails'=>(($xu_mails)?'checked="checked"':''),
		'gg'=>$xu_gg,		'tlen'=>$xu_tl,
		'icq'=>$xu_icq,	'sk'=>$xu_sk,
		'www'=>$xu_www,	'about'=>&$xu_ab );
	}
	else
	{
		$data=array(
		'mail'=>$user[UID]['mail'],
		'city'=>$user[UID]['city'],
		'mvis'=>(($user[UID]['mvis']==1)?'checked="checked"':''),
		'mails'=>(($user[UID]['mails']==1 || LOGD==2)?'checked="checked"':''),
		'gg'=>$user[UID]['gg'],
		'tlen'=>$user[UID]['tlen'],
		'icq'=>$user[UID]['icq'],
		'city'=>$user[UID]['city'],
		'www'=>$user[UID]['www'],
		'sk'=>$user[UID]['skype'],
		'about'=>&$user[UID]['about'] );
	}

	#Szablon
	include($catst.'account.php');
}
?>
