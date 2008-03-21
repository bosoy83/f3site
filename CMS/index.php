<?php
/* F3Site 3.0: copyright (C) 2007 COMPMaster
Skrypt rozpowszechniany na licencji GPL (wi�cej w license.txt i CZYTAJ.txt) */

#TYLKO W WERSJI ROBOCZEJ
$time1=microtime();

#J�dro
require('./kernel.php');

#�aduj modu� - gdy nie istnieje lub u�yto return, wy�wietl stron� 404
if(isset($_GET['co']) && strpos($_GET['co'],'/')===false && !isset($_GET['co'][30]))
{
	if(file_exists('./mod/'.$_GET['co'].'.php'))
	{
		if(!include './mod/'.$_GET['co'].'.php') $content->set404(); #Modu�?
	}
	elseif(file_exists('./plugins/'.$_GET['co'].'/default.php'))
	{
		if(!include './plugins/'.$_GET['co'].'/default.php') $content->set404(); #Wtyczka?
	}
	else
	{
		$content->set404(); #B��d 404 - strona nie istnieje
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

#Sk�rka
if(file_exists(STYLE_DIR.'body.php'))
{
	include(STYLE_DIR.'body.php');
}
else
{
	exit('ERROR: Style is not compiled!');
}
//DO USUNI�CIAAAAAAAAAAAAA!!!!!!!
echo '<br />TYLKO W WERSJI ROBOCZEJ:<br />Zu�ycie pami�ci: '.xdebug_memory_usage()/1024 .' KB, Max: '.xdebug_peak_memory_usage()/1024 ,' KB, Czas sk�adania: ',microtime()-$time1.' s, do��czonych plik�w: '.count(get_included_files());
?>
