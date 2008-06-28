<?php
if(iCMSa!=1 || !Admit('U') || !$id || ($id==1 && UID!=1)) exit;

require LANG_DIR.'profile.php';
$error = array(); //B³êdy

#Uaktualnienie
if($_POST)
{
	#Dane
	$u = array(
		'login' => Clean($_POST['login']),
		'about' => Clean($_POST['about']),
		'skype' => Clean($_POST['skype'],40),
		'mail'  => Clean($_POST['mail']),
		'city' => Clean($_POST['city']),
		'tlen' => Clean($_POST['tlen'],30),
		'www'  => Clean($_POST['www']),
		'gid' => (int)$_POST['gid'],
		'icq' => (is_numeric($_POST['icq'])) ? $_POST['icq'] : null,
		'gg'  => (is_numeric($_POST['gg'])) ? $_POST['gg'] : null );

	#Login
	if(isset($u['login'][21]) || !isset($u['login'][2]))
	{
		$error[] = $lang['eplerr'];
	}
	if(db_count('ID','users WHERE login="'.$u['login'].'" AND ID!='.$id)!==0)
	{
		$error[] = $lang['eploginex'];
	}

	#E-mail
	if(!preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$u['mail']))
	{
		$error[] = $lang['eperrm'];
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
			$db->prepare('UPDATE '.PRE.'users SET login=:login, mail=:mail,
			about=:about, www=:www, city=:city, icq=:icq, skype=:skype,
			tlen=:tlen, gg=:gg, gid=:gid WHERE ID='.$id) -> execute($u); //WYKONAJ

			$content->info($lang['u upd'], array( (MOD_REWRITE ? '/user/'.$id :
				'index.php?co=user&amp;id='.$id) => $lang['account'].' '.$u['login'])); return;
		}
		catch(PDOException $e)
		{
			$content->info($lang['error'].$e->errorInfo[2]);
		}
	}
}

#Pobierz dane
else
{
	$u = $db->query('SELECT * FROM '.PRE.'users WHERE ID='.$id)->fetch(2);
	#Brak?
	if(!$u) { $content->info($lang['noex']); return; }
}

#Funkcje
require './lib/user.php';

#Do szablonu
$content->data = array(
	'u' => &$u,
	'url' => 'adm.php?a=editUser&amp;id='.$id,
	'groups' => GroupList($u['gid'])
);