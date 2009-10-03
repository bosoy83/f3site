<?php /* Zarz±dzanie tre¶ci± - dla zalogowanych */
if(iCMS!=1 || LEVEL<2) return;
define('EC',1);
require LANG_DIR.'content.php';
require './lib/categories.php';

#Typ kategorii
$TYPE = isset($_GET['act']) && ctype_alnum($_GET['act']) ? $_GET['act'] : 0;

#Klasa zapisu + ostatnia kategoria
if($_POST)
{
	if(isset($_POST['cat'])) $_SESSION['lastCat'][$TYPE] = (int)$_POST['cat'];
	require './mod/edit/saver.class.php';
}
if(isset($_GET['catid']))
{
	$lastCat = (int)$_GET['catid'];
}
elseif(isset($_SESSION['lastCat'][$TYPE]))
{
	$lastCat = $_SESSION['lastCat'][$TYPE];
}
else
{
	require_once './cfg/content.php';
	$lastCat = $cfg['start'][$nlang];
}

#Akcja
if($TYPE)
{
	switch($TYPE)
	{
		case '5': (require './mod/edit/new.php') or $content->set404(); break;
		case '1': (require './mod/edit/art.php') or $content->set404(); break;
		case '2': (require './mod/edit/file.php') or $content->set404(); break;
		case '3': (require './mod/edit/img.php') or $content->set404(); break;
		case '4': (require './mod/edit/link.php') or $content->set404(); break;
		default: if(file_exists('./mod/edit/'.$TYPE.'.php')) {
			(require './mod/edit/'.$TYPE.'.php') or $content->set404();
		} else return;
	}
	unset($_POST); return 1;
}

#Tytu³
$content->title = $lang['mantxt'];
$content->file  = 'content';

#Ostatni komentarz
if(Admit('CM') && $c = $db->query('SELECT name,date FROM '.PRE.'comms ORDER BY ID DESC LIMIT 1')->fetch(3))
{
	$content->data = array('title'=>$c[0], 'last'=>genDate($c[1],1));
}
else
{
	$content->data['last'] = null;
}