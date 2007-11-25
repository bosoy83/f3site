<?php
/* F3Site 3.0: copyright (C) 2007 COMPMaster
Skrypt rozpowszechniany na licencji GPL (wiêcej w license.txt i CZYTAJ.txt) */

/* For testing only */
$__t=microtime();

require('kernel.php');
$title='';
$head='';

if(isset($_GET['co']))
{
	#Modu³
	$co=Clean(str_replace('/','',$_GET['co']),20);

	#Modu³y tre¶ci
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

#Tytu³
$title=(($title)?$title.' :: ':'').$cfg['title'];

#Kod HEAD
if($cfg['dkh']) $head.=$cfg['dkh'];

#Menu i skórka
require('./cache/menu'.$nlang.'.php');
require($catst.'/body.php');

#Uaktualnij sesjê u¿ytkownika
$_SESSION['userdata']=$user[UID];

//DO USUNIÊCIAAAAAAAAAAAAA!!!!!!!
echo 'TYLKO W WERSJI ROBOCZEJ:<br />Zu¿ycie pamiêci: '.xdebug_memory_usage()/1024 .' KB, Max: '.xdebug_peak_memory_usage()/1024 ,' KB, Czas sk³adania: ',microtime()-$__t.' s';
?>
