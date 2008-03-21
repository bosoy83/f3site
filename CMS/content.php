<?php
if(iCMS!=1) exit;
require('./cfg/c.php');

#Sta³a bezpieczeñstwa
define('CONTENT',1);

#Odczyt danych i ustawienie tytu³u strony do $title
switch($_GET['co'])
{
	#Art
	case 'art':
		$res=$db->query('SELECT t.*,f.text,f.page,f.opt FROM '.PRE.'arts t INNER JOIN '.PRE.'artstxt f ON t.ID=f.ID WHERE t.ID='.$id.' AND f.page='.((isset($_GET['page']))?$_GET['page']:1));
		break;

	#News
	case 'news':
		$res=$db->query('SELECT * FROM '.PRE.'news WHERE access=1 && ID='.$id);
		break;

	#Plik
	case 'file':
		$res=$db->query('SELECT * FROM '.PRE.'files WHERE access=1 && ID='.$id);
		break;

	#Obraz
	case 'img':
		$res=$db->query('SELECT * FROM '.PRE.'imgs WHERE access=1 && ID='.$id);
		break;

	default: return 'CT Error';
}

#Do tablicy
$content=$res->fetch(2);
$res=null;

#KATEGORIA
if($content && $co!='page')
{
	$res=$db->query('SELECT name,sc,type,opt,lft,rgt FROM '.PRE.'cats WHERE ID='.
		$content['cat'].' && access!=3');
	$cat=$res->fetch(2);
	$res=null;
}

#Dostêp
if($content && ($co==='page' || $cat))
{
	$title=$content['name'];
	define('MOD','./mod/'.$co.'.php');
}
else
{
	define('MOD','./404.php');
}
?>