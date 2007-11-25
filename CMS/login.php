<?php
require('kernel.php');
require('./lib/info.php');

#Wyloguj?
if(isset($_GET['logout']) && LOGD==1)
{
	unset($_SESSION['uid'],$_SESSION['uidl'],$_SESSION['uidp']);
	if(isset($_COOKIE[$cfg['c'].'login'])) setcookie($cfg['c'].'login','',time()-25920000);
	Notify(4,'index.php');
	exit;
}

#Loguj?
elseif(LOGD==2)
{
	#Wpisane dane
	$snduser=Clean($_POST['snduser'],30);
	$sndpass=Clean($_POST['sndpass'],30);
	$xpass=md5($sndpass);

	#Puste?
	if(empty($snduser) || empty($sndpass))
	{
		Header('Location: '.URL.((isset($_POST['fromadm']))?'adm.php':'index.php'));
		exit;
	}

	#Pobierz dane
	$res=$db->query('SELECT ID,login,pass,lv FROM '.PRE.'users WHERE login='.$db->quote($snduser));
	$u=$res->fetch(2); //ASSOC
	$res=null;

	#Ban?
	if($u['lv']===0)
	{
		Notify(16);
	}

	#Poprawne?
	elseif(strtolower($u['login'])===strtolower($snduser) && $u['pass']===$xpass)
	{
		#Nowe ID sesji dla bezpieczeñstwa
		session_regenerate_id(1);

		#Pamiêtanie
		if($_POST['sndr'])
		{
			setcookie($cfg['c'].'login',$u['ID'].':'.$xpass,time()+25920000) or exit('Cookies problem!');
		}
		else
		{
			$_SESSION['uid']=$u['ID'];
			$_SESSION['uidp']=$xpass;
			$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
		}
		Notify(1,((isset($_POST['fromadm']))?'adm.php':'index.php'));
	}
	else
	{
		sleep(3);
		require($catl.'special.php');
		Notify($lang['s2'].(($cfg['pswp']==1)?'<br /><br /><a href="index.php?co=psw">'.$lang['pswp'].'</a>':''));
	}
}
?>
