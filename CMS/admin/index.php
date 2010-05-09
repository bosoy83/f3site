<?php
chdir('..');
define('iCMS',1);
define('iCMSa',1);
require 'kernel.php';

#Niezalogowany?
if(!UID)
{
	Header('Location: '.URL.'login.php?from=admin');
	exit;
}
elseif(LEVEL < 3)
{
	Header('Location: '.URL); //Przekieruj na stronê g³ówn±
	exit;
}
require LANG_DIR.'adm.php';

#Katalog szablonów
$content->dir = './style/admin/';
$content->cache = './cache/admin/';

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
	if(admit($r)) return '<li class="a_'.$c.'"><a href="'.url($url,'','admin').'">'.$title.'</a></li>';
}

#Tryb konserwacji
if(isset($cfg['MA']))
{
	$content->info($lang['siteOff'], null, 'warning');
}

#Modu³
if(isset($URL[0]))
{
	$A = str_replace('/', '', $URL[0]);
	$A = str_replace('.', '', $A);
	if(file_exists('./admin/'.$A.'.php'))
	{
		(include './admin/'.$A.'.php') or $content->set404();
	}
	elseif(file_exists('./plugins/'.$A.'/admin.php'))
	{
		(include './plugins/'.$A.'/admin.php') or $content->set404();
	}
	else
	{
		$A = 'summary';
		include './admin/summary.php';
	}
}
else
{
	$A = 'summary';
	include './admin/summary.php';
}

#Wczytaj menu z sesji lub wygeneruj
if(isset($_SESSION['admenu']) && $_SESSION['admenu'] === LANG)
{
	$menu = $_SESSION['AdMenu'];
}
else
{
	$menu = '<ul class="adm">'.

	MI($lang['cats'],'cats','C','cat').
	MI($lang['polls'],'polls','Q','poll').
	MI($lang['ipages'],'pages','P','page').
	MI($lang['rss'],'rss','R','rss').

	'</ul><ul class="adm">'.

	MI($lang['users'],'users','U','user').
	MI($lang['admins'],'admins','U','user').
	MI($lang['groups'],'groups','G','user').
	MI($lang['log'],'log','L','log').
	MI($lang['mailing'],'mailing','M','mail').

	'</ul><ul class="adm">'.

	MI($lang['config'],'config','CFG','cfg').
	MI($lang['dbcopy'],'db','DB','db').
	MI($lang['nav'],'menu','N','menu').
	MI($lang['ads'],'ads','B','ads').
	MI($lang['plugs'],'plugins','E').

	'</ul>';

	#Rozszerzenia
	$res = $db->query('SELECT ID,text,file FROM '.PRE.'admmenu WHERE menu=1');
	$ex = '';
	foreach($res as $x)
	{
		$ex .= MI($x['text'],$x['file'],$x['ID']);
	}
	if($ex)
	{
		$menu .= '<ul class="adm">'.$ex.'</ul>';
	}

	#Zapisz uk³ad menu
	$_SESSION['admenu'] = LANG;
	$_SESSION['AdMenu'] = $menu;
	unset($ex,$res,$x);
}

#Szablon i tytu³
if(!$content->file) $content->file = $A;
if(!$content->title && isset($lang[$A])) $content->title = $lang[$A];

#¯±danie JS czy standardowe
if(JS)
{
	$content->display();
}
else
{
	require $content->path('admin', 1);
}