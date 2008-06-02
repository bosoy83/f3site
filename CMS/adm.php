<?php
$time1=microtime(1);

#J±dro
require('kernel.php');
define('iCMSa',1);

#Admin?
if(LOGD==1)
{
	if($user[UID]['lv']<3) exit('Brak praw dostêpu!');
}
require LANG_DIR.'adm.php';

#Zazn. ID
function GetIDs($v)
{
	$x=Array();
	$ile=count($v);
	for($i=0;$i<$ile;$i++)
	{
		if(is_numeric(key($v))) array_push($x,key($v)); next($v);
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

#Niezalogowany - logowanie
if(LOGD!==1 || defined('MOD'))
{
	require './admin/inc/login.php';
	return 1;
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

#Menu boczne (tytu³,plik,upr,klasa)
/*$menu = array(
	'cats'  => Admit('C'),
	'polls' => Admit('f3s'),
	'pages' => Admit('IP'),
	'rss'   => Admit('RSS'),
	'users' => Admit('U'),
	'admins'=> Admit('AD'),
	'groups'=> Admit('UG'),
	'log'   => Admit('LOG'),
	'mail'  => Admit('MM'),
	'config'=> Admit('CFG'),
	'db'    => Admit('CDB'),
	'nav'   => Admit('NM'),
	'ads'   => Admit('B'),
	'plugin'=> Admit('PI')
);*/

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
if(!$content->file) $content->file = 'admin/'.$A;
if(!$content->title && isset($lang[$A])) $content->title = $lang[$A];

#Skórka - admin
require VIEW_DIR.'admin.html';

//DO USUNIÊCIAAAAAAAAAAAAA!!!!!!!
$time2=microtime(1);
echo '<br />TYLKO W WERSJI ROBOCZEJ:<br />Zu¿ycie pamiêci: '.xdebug_memory_usage()/1024 .' KB, Max: '.xdebug_peak_memory_usage()/1024 ,' KB, Czas sk³adania: ',$time2-$time1.' s, do³±czonych plików: '.count(get_included_files());?>
