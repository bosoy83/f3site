<?php
#J±dro
require('kernel.php');
define('iCMSa',1);

#Admin?
if(LOGD==1)
{
	if($user[UID]['lv']<3) exit('Brak praw dostêpu!');
}
require($catl.'adm.php');

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

#Specjalne - w tym ¿±dania XMLHttpRequest
if(isset($_GET['x']))
{
	switch($_GET['x'])
	{
		case 'db': include('./admin/db.php'); break;
		case 'del': include('./admin/inc/head_del.php'); break;
		default:
			$x=str_replace('/','',$_GET['x']);
			$x=str_replace('.','',$x);
			if(file_exists('./plugins/'.$a.'/admin2.php')) require('./plugins/'.$a.'/admin2.php');
			unset($x);
	}
}

if(LOGD==1 && !defined('MOD'))
{ 
	#Modu³
	if(isset($_GET['a']))
	{
		$a=str_replace('/','',$_GET['a']);
		$a=str_replace('.','',$a);
		if(file_exists('./admin/'.$a.'.php'))
		{
			define('MOD','./admin/'.$a.'.php');
		}
		elseif(file_exists('./plugins/'.$a.'/admin.php'))
		{
			define('MOD','./plugins/'.$a.'/admin.php');
		}
	}
	else
	{
		define('MOD','./admin/summary.php');
	}
	require_once($catst.'global.php');

	#Menu boczne (tytu³,plik,upr,klasa)
	$menu='<div class="adm"><ul>'.

	MI($lang['cats'],'cats','C','cat').
	MI($lang['polls'],'polls','f3s','poll').
	MI($lang['ipages'],'pages','IP','page').

	'</ul></div><div class="adm"><ul>'.

	MI($lang['users'],'users','U','user').
	MI($lang['admins'],'adms','AD','user').
	MI($lang['groups'],'groups','UG','user').
	MI($lang['log'],'log','LOG','log').
	MI($lang['mailing'],'mailing','MM','mail').

	'</ul></div><div class="adm"><ul>'.

	MI($lang['conf'],'config','CFG','cfg').
	MI($lang['dbcopy'],'db','CDB','db').
	MI($lang['nav'],'nav','NM','menu').
	MI($lang['ads'],'ads','B','ads').
	MI($lang['plugs'],'plugins','PI').

	'</ul></div>';

	#Wtyczki
	$menu.=file_get_contents('./cache/pluginmenu.php');

	#Styl
	require_once($catst.'/admin.php');
}
else
{
	require('./admin/inc/login.php');
} ?>
