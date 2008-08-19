<?php
define('iCMS',1);
define('iCMSa',1);
require 'kernel.php';

#Niezalogowany?
if(LOGD != 1)
{
	Header('Location: '.URL.'login.php?admin');
	exit;
}
elseif(LEVEL < 3)
{
	Header('Location: '.URL.'index.php'); //Przekieruj na stronê g³ówn±
	exit;
}
require LANG_DIR.'adm.php';

#Katalog szablonów
$content->dir = './style/admin/';
$content->cacheDir = './cache/admin/';

#Zazn. ID
function GetID($toStr=false, $array=null)
{
	$x = array();
	if(!$array && isset($_POST['x'])) $array = $_POST['x']; //Domy¶lny klucz: x
	if(!$array) return false;

	foreach($array as $key=>$val)
	{
		if(is_numeric($key)) $x[] = $key;
	}
	if(!$x) return false;

	#Zwróæ tablicê / string
	return $toStr ? join(',', $x) : $x;
}

#Menu boczne
function MI($title,$url,$r,$c='plug')
{
	if(Admit($r)) return '<li class="a_'.$c.'"><a href="?a='.$url.'">'.$title.'</a></li>';
}

#Modu³
if(isset($_GET['a']))
{
	$A = str_replace('/', '', $_GET['a']);
	$A = str_replace('.', '', $A);
	if(file_exists('./admin/'.$A.'.php'))
	{
		include './admin/'.$A.'.php';
	}
	elseif(file_exists('./plugins/'.$A.'/admin.php'))
	{
		include './plugins/'.$A.'/admin.php';
	}
	else include './admin/summary.php';
}
else
{
	$A = 'summary';
	include './admin/summary.php';
}

#Menu
if(isset($_SESSION['admmenu']))
{
	$menu = file_get_contents('./cache/adm'.UID.'.php');
}
else
{
	$menu='<div class="adm"><ul>'.

	MI($lang['cats'],'cats','C','cat').
	MI($lang['polls'],'polls','f3s','poll').
	MI($lang['ipages'],'pages','IP','page').
	MI($lang['rss'],'summary','RSS','rss').

	'</ul></div><div class="adm"><ul>'.

	MI($lang['users'],'users','U','user').
	MI($lang['admins'],'admins','AD','user').
	MI($lang['groups'],'groups','UG','user').
	MI($lang['log'],'log','LOG','log').
	MI($lang['mailing'],'mailing','MM','mail').

	'</ul></div><div class="adm"><ul>'.

	MI($lang['config'],'config','CFG','cfg').
	MI($lang['dbcopy'],'db','CDB','db').
	MI($lang['nav'],'menu','NM','menu').
	MI($lang['ads'],'ads','B','ads').
	MI($lang['plugs'],'plugins','PI').

	'</ul></div>';

	#Wtyczki
	$menu .= file_get_contents('./cache/pluginmenu.php');

	#Zapisz uk³ad menu
	if(file_put_contents('./cache/adm'.UID.'.php', $menu))
	{
		$_SESSION['admmenu'] = true;
	}
}

#Szablon i tytu³
if(!$content->file) $content->file = $A;
if(!$content->title && isset($lang[$A])) $content->title = $lang[$A];

#Kompiluj szablon, gdy potrzeba...
if($content->check && filemtime('./style/admin/admin.html') > @filemtime('./cache/admin/admin.html'))
{
	$content->compile('admin.html');
}

#Skórka - admin
require './cache/admin/admin.html';