<?php
if(iCMSa!=1 || !admit('U')) exit;
require LANG_DIR.'profile.php';
require './cfg/account.php';

#Tytu³ i JS
$content->title = $lang['account'];
$content->addScript('lib/forms.js');

#User ID
if(!$id || ($id==1 && UID!=1)) return;

#B³êdy
$error = array();

#Uaktualnienie
if($_POST)
{
	#Dane
	$u = array(
		'login' => clean($_POST['login']),
		'about' => clean($_POST['about']),
		'skype' => clean($_POST['skype'],40),
		'jabber'=> clean($_POST['jabber'],60),
		'photo' => clean($_POST['photo']),
		'mail'  => clean($_POST['mail']),
		'city' => clean($_POST['city']),
		'tlen' => clean($_POST['tlen'],30),
		'www'  => clean($_POST['www']),
		'icq' => (is_numeric($_POST['icq'])) ? $_POST['icq'] : null,
		'gg'  => (is_numeric($_POST['gg'])) ? $_POST['gg'] : null);

	#Login
	if(isset($u['login'][21]) || !isset($u['login'][2]))
	{
		$error[] = $lang['eplerr'];
	}
	if(dbCount('users WHERE login="'.$u['login'].'" AND ID!='.$id)!==0)
	{
		$error[] = $lang['eploginex'];
	}
	switch($cfg['logins'])
	{
		case 1: $re = '/^[A-Za-z0-9 _-]*$/'; break;
		case 2: $re = '/^[0-9\pL _.-]*$/u'; break;
		default: $re = '/^[^&/?#=]$/'; break;
	}
	if(!preg_match($re, $u['login']))
	{
		$error[] = $lang['loginChar'];
	}

	#E-mail
	if(!preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$u['mail']))
	{
		$error[] = $lang['badMail'];
	}
	
	#WWW
	$u['www'] = str_replace('javascript:', 'java_script', $u['www']);
	$u['www'] = str_replace('vbscript:', 'vb_script', $u['www']);

	#B³¹d?
	if($error)
	{
		$content->info('<ul><li>'.join('</li><li>',$error).'</li></ul>');
	}
	#Zapis
	else
	{
		try
		{
			$db->prepare('UPDATE '.PRE.'users SET login=:login, mail=:mail, about=:about,
			www=:www, city=:city, icq=:icq, skype=:skype, tlen=:tlen, jabber=:jabber,
			gg=:gg, photo=:photo WHERE ID='.$id) -> execute($u);

			$content->info($lang['upd'], array(url('user/'.$id) => $u['login']));
			return 1;
		}
		catch(PDOException $e)
		{
			$content->info($lang['error'].$e);
		}
	}
}

#Pobierz dane
elseif(!$u = $db->query('SELECT * FROM '.PRE.'users WHERE ID='.$id)->fetch(2))
{
	$content->info($lang['noex']); return;
}

#Funkcje
require './lib/user.php';

#Do szablonu
$content->data = array(
	'u' => &$u,
	'url' => url('editUser/'.$id, '', 'admin'),
	'fileman'=> admit('FM')
);