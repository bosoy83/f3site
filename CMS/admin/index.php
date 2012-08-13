<?php
chdir('..');
define('iCMS',1);
define('iCMSa',1);
require 'kernel.php';

#Not logged in
if(!UID)
{
	header('Location: '.URL.'login.php?from=admin');
	exit;
}
elseif(!IS_ADMIN)
{
	header('Location: '.URL); //Redirect to homepage
	exit;
}
require LANG_DIR.'adm.php';

#Templates folder
$content->dir = './style/admin/';
$content->cache = './cache/admin/';

#Function: Get selected ID
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

	#Return array or string
	return $toStr ? join(',', $x) : $x;
}

#Function: bar menu
function MI($title,$url,$r,$c='plug')
{
	if(admit($r)) return '<li class="a_'.$c.'"><a href="'.url($url,'','admin').'">'.$title.'</a></li>';
}

#Maintenance mode info
if(isset($cfg['MA']))
{
	$content->info($lang['siteOff'], null, 'warning');
}

#Load module
if(isset($URL[0]))
{
	$A = str_replace('/', '', $URL[0]);
	$A = str_replace('.', '', $A);
	if(file_exists('./admin/'.$A.'.php'))
	{
		include './admin/'.$A.'.php';
	}
	elseif(file_exists('./plugins/'.$A.'/admin.php'))
	{
		include './plugins/'.$A.'/admin.php';
	}
	elseif(file_exists('./style/admin/'.$A.'.html'))
	{
		$content->add($A);
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

#Lod menu from session or generate new
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
	MI($lang['groups'],'groups','G','user').
	MI($lang['log'],'log','L','log').
	MI($lang['mailing'],'mailing','M','mail').

	'</ul><ul class="adm">'.

	MI($lang['config'],'config','CFG','cfg').
	MI($lang['dbcopy'],'db','DB','db').
	MI($lang['nav'],'menu','N','menu').
	MI($lang['plugs'],'plugins','E').

	'</ul>';

	#Addons
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

	#Store menu in session
	$_SESSION['admenu'] = LANG;
	$_SESSION['AdMenu'] = $menu;
	unset($ex,$res,$x);
}

#Default title
if(!$content->title && isset($lang[$A])) $content->title = $lang[$A];

#¯±danie JS czy standardowe
if(JS)
{
	$content->display();
}
else
{
	$content->front('admin', array('menu'=>$menu));
}