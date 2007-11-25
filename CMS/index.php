<?php
/* F3Site 3.0: copyright (C) 2007 COMPMaster
Skrypt rozpowszechniany na licencji GPL (wi�cej w license.txt i CZYTAJ.txt) */

/* For testing only */
$__t=microtime();

require('kernel.php');
$title='';
$head='';

if(isset($_GET['co']))
{
	#Modu�
	$co=Clean(str_replace('/','',$_GET['co']),20);

	#Modu�y tre�ci
	if($co=='art' || $co=='news' || $co=='file' || $co=='page' || $co=='img')
	{
		require('./content.php');
	}
	#Inne
	elseif(file_exists('./mod/'.$co.'.php'))
	{
		define('MOD','./mod/'.$co.'.php');
	}
	#Wtyczka
	elseif(file_exists('./plugins/'.$co.'/default.php'))
	{
		if(file_exists('./plugins/'.$co.'/head.php')) include('./plugins/'.$co.'/head.php');
		if(!defined('MOD')) define('MOD','./plugins/'.$co.'/default.php');
	}
	#404
	else
	{
		define('MOD','./404.php');
	}
	unset($co);
}

#Kategoria | strona
else
{
	include('./cfg/c.php');
	require('./lib/category.php');
}

#Tytu�
$title=(($title)?$title.' :: ':'').$cfg['title'];

#Kod HEAD
if($cfg['dkh']) $head.=$cfg['dkh'];

#Menu i sk�rka
require('./cache/menu'.$nlang.'.php');
require($catst.'/body.php');

#Uaktualnij sesj� u�ytkownika
$_SESSION['userdata']=$user[UID];

//DO USUNI�CIAAAAAAAAAAAAA!!!!!!!
echo 'TYLKO W WERSJI ROBOCZEJ:<br />Zu�ycie pami�ci: '.xdebug_memory_usage()/1024 .' KB, Max: '.xdebug_peak_memory_usage()/1024 ,' KB, Czas sk�adania: ',microtime()-$__t.' s';
?>
