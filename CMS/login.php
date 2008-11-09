<?php
define('iCMS',1);
require 'kernel.php';

#Gdzie przekierowaæ?
$from = isset($_GET['from']) && ctype_alnum($_GET['from']) ? $_GET['from'] : 'index';

#Wyloguj?
if(isset($_GET['logout']) && LOGD==1)
{
	unset($_SESSION['uid'],$_SESSION['userdata'],$_SESSION['uidl'],$_SESSION['uidp']);
	if(isset($_COOKIE[PRE.'login'])) setcookie(PRE.'login','',time()-25920000);
	$content->message(4,'index.php');
	exit;
}

#Loguj?
elseif(LOGD==2 && !empty($_POST['u']) && !empty($_POST['p']))
{
	#Wpisane dane
	$login = Clean($_POST['u'],30);
	$pass  = Clean($_POST['p'],30);
	$md5   = md5($pass);

	#Pobierz dane
	$res = $db->query('SELECT ID,login,pass,lv FROM '.PRE.'users WHERE login='.$db->quote($login));
	$u = $res->fetch(2); //ASSOC
	$res = null;

	#Nieaktywny?
	if($u['lv']===0)
	{
		$content->message(16);
	}

	#Poprawne?
	elseif(strtolower($u['login'])===strtolower($login) && $u['pass']===$md5)
	{
		#Nowe ID sesji dla bezpieczeñstwa
		session_regenerate_id(1);

		#Pamiêtanie
		if(isset($_POST['auto']))
		{
			setcookie(PRE.'login',$u['ID'].':'.$md5,time()+25920000) or exit('Cookies problem!');
		}
		else
		{
			$_SESSION['uid'] = $u['ID'];
			$_SESSION['uidp'] = $md5;
			$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
		}
		$content->message(1, $from.'.php');
	}
	else
	{
		sleep(3);
		$content->message(2);
	}
}

#Formularz logowania
$content->file = 'login';
$content->data['url'] = 'login.php?from='.$from;
$content->display();