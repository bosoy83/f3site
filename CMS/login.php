<?php
define('iCMS',1);
require 'kernel.php';

#Redirect to...
$from = isset($_GET['from']) && ctype_alnum($_GET['from']) ? $_GET['from'] : '';
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$to = empty($from) && $ref && strpos($ref,'login.php')===false ? $ref : URL;

#Bad referer
if($ref && strpos($ref,URL)!==0) { header('Location: '.URL); exit; }

#Logoff
if(isset($_GET['logout']) && UID)
{
	session_destroy();
	if(isset($_COOKIE[PRE.'login'])) setcookie(PRE.'login','',time()-31104000);
	header('Location: '.URL);
	$view->message(4, URL);
	exit;
}

#Register
elseif(isset($_POST['reg']))
{
	header('Location: '.URL.url('account', $_POST['u'] ? 'u='.urlencode($_POST['u']) : ''));
	exit;
}

#Login
elseif(!UID && !empty($_POST['u']) && !empty($_POST['p']))
{
	#Clean input
	$login = clean($_POST['u'],30);
	$pass  = clean($_POST['p'],30);
	$md5   = md5($pass);

	#Get user - ASSOC
	$res = $db->query('SELECT ID,login,pass,lv FROM '.PRE.'users WHERE login='.$db->quote($login));
	$u = $res->fetch(2);
	$res = null;

	#Inactive
	if($u['lv']=='0')
	{
		$view->message(16);
	}

	#Check login and password
	elseif(strtolower($u['login'])===strtolower($login) && $u['pass']===$md5)
	{
		#Regenerate session ID for better security
		session_destroy();
		session_start();
		session_regenerate_id(1);

		#Remember user
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
		header('Location: '.$to.$from);
		$view->message(1, $from ? $from : $to);
	}
	else
	{
		$view->message(2);
	}
}

#Show form
$view->add('login', array('url' => 'login.php?from='.$from));
$view->display();
