<?php /* Zarz±dzanie tre¶ci± - dla zalogowanych */
if(iCMS!=1 || LOGD!=1) return;
define('EC',1);
require LANG_DIR.'content.php';
require './lib/categories.php';

#Klasa zapisu + ost. kat.
if($_POST)
{
	if(isset($_POST['cat'])) $_SESSION['lastCat'] = (int)$_POST['cat'];
	require './mod/edit/saver.class.php';
}
if(isset($_GET['catid']))
{
	$lastCat = (int)$_GET['catid'];
}
else
{
	$lastCat = isset($_SESSION['lastCat']) ? $_SESSION['lastCat'] : 1;
}

#Akcja
if(isset($_GET['act']))
{
	switch($_GET['act'])
	{
		case 'new';  case '5': require('./mod/edit/new.php'); break;
		case 'art';  case '1': require('./mod/edit/art.php'); break;
		case 'file'; case '2': require('./mod/edit/file.php'); break;
		case 'img';  case '3': require('./mod/edit/img.php'); break;
		case 'link'; case '4': require('./mod/edit/link.php'); break;
		default: 
			if(ctype_alnum($_GET['act']) && file_exists('./mod/edit/'.$_GET['act'].'.php'))
			{
				require './mod/edit/'.$_GET['act'].'.php';
			}
			else return;
	}
	unset($last_cat,$id,$_POST); return 1;
}

#Tytu³
$content->title = $lang['mantxt'];
$content->file  = 'content';