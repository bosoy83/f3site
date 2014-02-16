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
$view->dir = './style/admin/';
$view->cache = './cache/admin/';

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

#Maintenance mode info
if(isset($cfg['MA']))
{
	$view->info($lang['siteOff'], null, 'warning');
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
		$view->add($A);
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

#Build module list
$modules = array(
	array($lang['cats'], 'cats', 'C'),
	array($lang['polls'], 'polls', 'Q'),
	array($lang['ipages'], 'pages', 'P'),
	//array($lang['rss'], 'rss', 'R'),
	array($lang['users'], 'users', 'U'),
	array($lang['groups'], 'groups', 'G'),
	array($lang['log'], 'log', 'L'),
	array($lang['mailing'], 'mailing', 'M'),
	array($lang['config'], 'config', 'CFG'),
	array($lang['nav'], 'menu', 'N'),
	array($lang['dbcopy'], 'db', 'DB'),
	array($lang['plugs'], 'plugins', 'E')
);

#Addons
$res = $db->query('SELECT ID,text,file FROM '.PRE.'admmenu WHERE menu=1');
foreach($res as $x)
{
	$modules[] = array($x['text'],$x['file'],$x['ID']);
}

#Build menu for admin
$menu = array();
foreach($modules as $x)
{
	if(admit($x[2])) $menu[] = array('text'=>$x[0],'url'=>url($x[1],null,'admin'),'class'=>$x[1]);
}

#Default title
if(!$view->title && isset($lang[$A])) $view->title = $lang[$A];

#¯±danie JS czy standardowe
if(JS)
{
	$view->display();
}
else
{
	$view->front('admin', array('menu'=>$menu));
}
