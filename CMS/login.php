<?php
define('iCMS',1);
require 'kernel.php';

#Wyloguj?
if(isset($_GET['logout']) && LOGD==1)
{
	unset($_SESSION['uid'],$_SESSION['uidl'],$_SESSION['uidp']);
	if(isset($_COOKIE[PRE.'login'])) setcookie(PRE.'login','',time()-25920000);
	$content->message(4,'index.php');
	exit;
}

#Loguj?
elseif(LOGD==2 && $_POST)
{
	#Wpisane dane
	$snduser = Clean($_POST['snduser'],30);
	$sndpass = Clean($_POST['sndpass'],30);
	$passMD5 = md5($sndpass);

	#Puste?
	if(empty($snduser) || empty($sndpass))
	{
		Header('Location: '.URL.((isset($_POST['fromadm']))?'adm.php':'index.php'));
		exit;
	}

	#Pobierz dane
	$res = $db->query('SELECT ID,login,pass,lv FROM '.PRE.'users WHERE login='.$db->quote($snduser));
	$u = $res->fetch(2); //ASSOC
	$res = null;

	#Ban?
	if($u['lv']===0)
	{
		$content->message(16);
	}

	#Poprawne?
	elseif(strtolower($u['login'])===strtolower($snduser) && $u['pass']===$passMD5)
	{
		#Nowe ID sesji dla bezpieczeñstwa
		session_regenerate_id(1);

		#Pamiêtanie
		if(isset($_POST['sndr']))
		{
			setcookie(PRE.'login',$u['ID'].':'.$passMD5,time()+25920000) or exit('Cookies problem!');
		}
		else
		{
			$_SESSION['uid'] = $u['ID'];
			$_SESSION['uidp'] = $passMD5;
			$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
		}
		$content->message(1,((isset($_POST['fromadm']))?'adm.php':'index.php'));
	}
	else
	{
		sleep(3);
		$content->message(2);
	}
}