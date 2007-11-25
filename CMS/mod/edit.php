<?php /* Zarz±dzanie tre¶ci± */
if(iCMS!=1) exit;
require($catl.'content.php');
require('./lib/categories.php');
define('EC',1);

#Niezalogowany?
if(LOGD!=1) { Info($lang['noex']); return; }

#Klasa zapisu + ost. kat.
if($_POST)
{
	if(isset($_POST['xu_c'])) $_SESSION['last_cat']=(int)$_POST['xu_c'];
	require('./mod/edit/saver.class.php');
}
$last_cat=isset($_SESSION['last_cat'])?$_SESSION['last_cat']:1;

#ID
$id=isset($_GET['id'])?$_GET['id']:0;

#Akcja
if(isset($_GET['act']))
{
	switch($_GET['act'])
	{
		case 'new': require('./mod/edit/editnew.php'); break;
		case 'art': require('./mod/edit/editart.php'); break;
		case 'file': require('./mod/edit/editfile.php'); break;
		case 'img': require('./mod/edit/editimg.php'); break;
		case 'link': require('./mod/edit/editlink.php'); break;
		case 'text': require('./mod/edit/texts.php'); break;
		default: require('./mod/edit/list.php');
	}
	unset($last_cat,$id,$_POST); return;
}

#Podsumowanie
include($catst.'content.php');
?>
