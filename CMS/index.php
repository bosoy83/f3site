<?php
/* F3Site 3.0: copyright (C) 2007 COMPMaster
Skrypt rozpowszechniany na licencji GPL (wiêcej w license.txt i CZYTAJ.txt) */

#TYLKO W WERSJI ROBOCZEJ
$time1=microtime();

#J±dro
require('./kernel.php');

#£aduj modu³ - gdy nie istnieje lub u¿yto return, wy¶wietl stronê 404
if(isset($_GET['co']) && strpos($_GET['co'],'/')===false && !isset($_GET['co'][30]))
{
	if(file_exists('./mod/'.$_GET['co'].'.php'))
	{
		if(!include './mod/'.$_GET['co'].'.php') $content->set404(); #Modu³?
	}
	elseif(file_exists('./plugins/'.$_GET['co'].'/default.php'))
	{
		if(!include './plugins/'.$_GET['co'].'/default.php') $content->set404(); #Wtyczka?
	}
	else
	{
		$content->set404(); #B³±d 404 - strona nie istnieje
	}
}

#Kategoria
else
{
	include('./cfg/c.php');
	if(!include('./lib/category.php')) $content->set404();
}

#Kod HEAD
if($cfg['dkh']) $content->head .= $cfg['dkh'];

#Szablon
if(!$content->file && isset($_GET['co'])) $content->file = $_GET['co'];

#Menu
require './cache/menu'.$nlang.'.php';

#Skórka
if(file_exists(STYLE_DIR.'body.php'))
{
	include(STYLE_DIR.'body.php');
}
else
{
	exit('ERROR: Style is not compiled!');
}
//DO USUNIÊCIAAAAAAAAAAAAA!!!!!!!
echo '<br />TYLKO W WERSJI ROBOCZEJ:<br />Zu¿ycie pamiêci: '.xdebug_memory_usage()/1024 .' KB, Max: '.xdebug_peak_memory_usage()/1024 ,' KB, Czas sk³adania: ',microtime()-$time1.' s, do³±czonych plików: '.count(get_included_files());
?>
