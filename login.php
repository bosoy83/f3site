<?php
require_once('kernel.php');

#Wyloguj?
if($_GET['logout']==1 && LOGD==1)
{
	unset($_SESSION['uid'],$_SESSION['uidl'],$_SESSION['uidp']);
	if(isset($_COOKIE[$cfg['c'].'login'])) setcookie($cfg['c'].'login','',time()-25920000);
	define('SPECIAL',4);
	define('WHERE','index.php');
	require('special.php');
	exit;
}

#Loguj?
elseif(LOGD==2)
{
	#Wpisane dane
	$snduser=TestForm($_POST['snduser'],1,1,1);
	$sndpass=TestForm($_POST['sndpass'],1,1,1);
	$xpass=md5($sndpass);
	unset($tmpu);
	
	#Puste?
	if(empty($snduser) || empty($sndpass))
	{
		Header(URL.(($_POST['fromadm'])?'adm.php':'index.php'));
		exit;
	}
	
	#Pobierz dane
	db_read('ID,login,pass,lv','users','tmpu','oa',' WHERE login="'.db_esc($snduser).'"');
		
	#Ban?
	if($tmpu['lv']==5)
	{
		define('SPECIAL',16);
	}

	#Poprawne?
	elseif(strtolower($tmpu['login'])===strtolower($snduser) && $tmpu['pass']===$xpass)
	{
		#Pamiêtanie
		if($_POST['sndr'])
		{
			setcookie($cfg['c'].'login',$tmpu['ID'].':'.$xpass,time()+25920000) or exit('Cookies problem!');
		}
		else
		{
			$_SESSION['uid']=$tmpu['ID'];
			$_SESSION['uidl']=$tmpu['login'];
			$_SESSION['uidp']=$xpass;
			$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
		}
		define('SPECIAL',1);
		define('WHERE',(($_POST['fromadm'])?'adm.php':'index.php'));
	}
	else
	{
		require($catl.'special.php');
		define('SPECIAL',$lang['s2'].(($cfg['pswp']==1)?'<br /><br /><a href="index.php?co=psw">'.$lang['pswp'].'</a>':''));
	}
	require('special.php');
}
?>
