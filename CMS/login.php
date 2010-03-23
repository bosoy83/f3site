<?php
define('iCMS',1);
require 'kernel.php';

#Gdzie przekierować?
$from = isset($_GET['from']) && ctype_alnum($_GET['from']) ? $_GET['from'] : '';

#Wyloguj?
if(isset($_GET['logout']) && UID)
{
	session_destroy();
	if(isset($_COOKIE[PRE.'login'])) setcookie(PRE.'login','',time()-31104000);
	$content->message(4, '.');
	exit;
}

#Rejestruj?
elseif(isset($_POST['reg']))
{
	header('Location: '.URL.url('account', $_POST['u'] ? 'u='.urlencode($_POST['u']) : ''));
	exit;
}

#Loguj?
elseif(!UID && !empty($_POST['u']) && !empty($_POST['p']))
{
	#Wpisane dane
	$login = clean($_POST['u'],30);
	$pass  = clean($_POST['p'],30);
	$md5   = md5($pass);

	#Pobierz dane
	$res = $db->query('SELECT ID,login,pass,lv FROM '.PRE.'users WHERE login='.$db->quote($login));
	$u = $res->fetch(2); //ASSOC
	$res = null;

	#Nieaktywny?
	if($u['lv']=='0')
	{
		$content->message(16);
	}

	#Poprawne?
	elseif(strtolower($u['login'])===strtolower($login) && $u['pass']===$md5)
	{
		#Nowe ID sesji dla bezpieczeństwa
		session_destroy();
		session_start();
		session_regenerate_id(1);

		#Pamiętanie
		if(isset($_POST['auto']))
		{
			setcookie(PRE.'login',$u['ID'].':'.$md5,time()+31104000,PATH,null,0,1);
		}
		else
		{
			$_SESSION['uid'] = $u['ID'];
			$_SESSION['uidp'] = $md5;
			$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
		}
		if(isset($_SESSION['online']))
		{
			unset($_SESSION['online']);
		}
		$content->message(1, $from ? $from : '.');
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