<?php
$time1=microtime(1);

#J�dro
define('iCMS',1);
define('iCMSa',1);
require 'kernel.php';

#Niezalogowany?
if(LOGD != 1)
{
	$content->file = 'login';
	exit;
}
elseif(LEVEL < 3)
{
	Header(URL.'index.php'); //Przekieruj na stron� g��wn�
	exit;
}
require LANG_DIR.'adm.php';

#Katalog szablon�w
$content->dir = './style/admin/';
$content->cacheDir = './cache/admin/';

#Zazn. ID
function GetIDs($v)
{
	$x=Array();
	$ile=count($v);
	for($i=0;$i<$ile;$i++)
	{
		if(is_numeric(key($v))) $x[] = key($v); next($v);
	}
	return $x;
}

#Typ
function typeOf($co)
{
	switch($co)
	{
		case 2: return 'files'; break;
		case 3: return 'imgs'; break;
		case 4: return 'links'; break;
		case 5: return 'news'; break;
		default: return 'arts';
	}
}

#Menu boczne
function MI($title,$url,$r,$c='plug')
{
	if(Admit($r)) return '<li class="a_'.$c.'"><a href="?a='.$url.'">'.$title.'</a></li>';
}

#Modu�
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

	#Zapisz uk�ad menu
	if(file_put_contents('./cache/adm'.UID.'.php', $menu))
	{
		$_SESSION['admmenu'] = true;
	}
}

#Szablon i tytu�
if(!$content->file) $content->file = $A;
if(!$content->title && isset($lang[$A])) $content->title = $lang[$A];

#Sk�rka - admin
require VIEW_DIR.'admin.html';

//DO USUNI�CIAAAAAAAAAAAAA!!!!!!!
$time2=microtime(1);
echo '<br />TYLKO W WERSJI ROBOCZEJ:<br />Zu�ycie pami�ci: '.xdebug_memory_usage()/1024 .' KB, Max: '.xdebug_peak_memory_usage()/1024 ,' KB, Czas sk�adania: ',$time2-$time1.' s, do��czonych plik�w: '.count(get_included_files());