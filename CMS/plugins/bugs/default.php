<?php
if(iCMS!=1) exit;

#Opcje
require './cfg/bugs.php';

#Jêzyk
if(file_exists('./plugins/bugs/lang/'.$nlang.'.php'))
{
	require './plugins/bugs/lang/'.$nlang.'.php';
}
else
{
	require './plugins/bugs/lang/en.php';
}

#Prawa
function BugRights($x)
{
	global $user;
	switch($x)
	{
		case '': case NULL: if(Admit('BUGS')) return true; break;
		case 'ALL': return true; break;
		case 'LOGD': if(LOGD==1) return true; break;
		default:
		if(LOGD==1)
		{
			$r = explode(' ', $x);
			if(in_array('U:'.UID, $r) || in_array('G:'.GID, $r)) return true;
		}
	}
	return false;
}

#Nowe wpisy?
function BugIsNew($d1,$d2)
{
	if(empty($d1)) $d1 = $_SESSION['recent'];
	if(empty($d2)) return false;
	return (strtotime($d2) > strtotime($d1));
}

#Katalog szablonów
$content->dir = './plugins/bugs/style/';
$content->cache = './cache/bugs/';
$content->addCSS('plugins/bugs/style/bugs.css');

#Modu³
if(!isset($cfg['bugsOn']))
{
	$content->info($lang['bugsOff']);
	return 1;
}

#Akcja
if(isset($_GET['act']))
{
	switch($_GET['act'])
	{
		case 'e': require 'plugins/bugs/edit.php'; break;
		case 'l': require 'plugins/bugs/list.php'; break;
		default: return;
	}
}
elseif($id)
{
	require 'plugins/bugs/view.php';
}
else
{
	require 'plugins/bugs/cats.php';
}