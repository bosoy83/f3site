<?php
if(iCMS!=1 || LEVEL<2) return;
define('EC',1);
require LANG_DIR.'content.php';
require './lib/categories.php';
require './cfg/content.php';

#Typ kategorii i ID obiektu
$TYPE = isset($URL[1]) && ctype_alnum($URL[1]) ? $URL[1] : 0;
$id   = isset($URL[2]) && is_numeric($URL[2]) ? $URL[2] : 0;

#Klasa zapisu + ostatnia kategoria
if($_POST)
{
	if(isset($_POST['cat'])) $_SESSION['LASTCAT'][$TYPE] = (int)$_POST['cat'];
	require './mod/edit/saver.class.php';
}
if(isset($_GET['catid']))
{
	$lastCat = (int)$_GET['catid'];
}
elseif(isset($_SESSION['LASTCAT'][$TYPE]))
{
	$lastCat = $_SESSION['LASTCAT'][$TYPE];
}
else
{
	$lastCat = $cfg['start'][LANG];
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
		case 'gallery': (require './mod/edit/photos.php') or $content->set404(); break;
		default: if(file_exists('./mod/edit/'.$TYPE.'.php')) {
			(require './mod/edit/'.$TYPE.'.php') or $content->set404();
		} else return;
	}
	unset($_POST); return 1;
}

#Tytul
$content->title = $lang['mantxt'];
$content->file  = 'content';

#Ostatni komentarz
if(admit('CM') && $c = $db->query('SELECT name,date,text FROM '.PRE.'comms ORDER BY ID DESC LIMIT 1')->fetch(3))
{
	if(isset($cfg['bbcode']))
	{
		require_once './lib/bbcode.php';
		$c[2] = BBCode($c[2]);
	}
	$content->data = array(
		'title' => $c[0],
		'last'  => genDate($c[1],1),
		'text'  => emots($c[2]),
		'color' => isset($cfg['colorCode']),
	);
}
else
{
	$content->data = array('last' => null);
}

#Wolne strony
$content->data += array(
'page' => admit('P') ? url('editPage','','admin') : null,
'pages' => admit('P') ? url('pages','','admin') : null);