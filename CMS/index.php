<?php
/* F3Site 3.0: copyright (C) 2007 COMPMaster
Skrypt rozpowszechniany na licencji GPL (wi�cej w license.txt i CZYTAJ.txt) */

#TYLKO W WERSJI ROBOCZEJ
#$time1=microtime(1);

#J�dro
define('iCMS',1);
require './kernel.php';

#�aduj modu� - gdy nie istnieje lub u�yto return, wy�wietl stron� 404
if(isset($_GET['co']) && strpos($_GET['co'],'/')===false && !isset($_GET['co'][30]))
{
	#Szablon
	$content->file = array($_GET['co']);

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
	include('./cfg/content.php');
	if(!include('./lib/category.php')) $content->set404();
}

#Kod HEAD
if($cfg['head']) $content->head .= $cfg['head'];

#Menu
require './cache/menu'.$nlang.'.php';

#Kompiluj sk�rk�, gdy potrzeba...
if($content->check && filemtime(SKIN_DIR.'body.html') > @filemtime(VIEW_DIR.'body.html'))
{
	$content->compile('body.html');
}

#Sk�rka
include VIEW_DIR.'body.html';

/*$time2=microtime(1);
echo '<br />TYLKO W WERSJI ROBOCZEJ:<br />Zu�ycie pami�ci: '.xdebug_memory_usage()/1024 .' KB, Max: '.xdebug_peak_memory_usage()/1024 ,' KB, Czas sk�adania: ',$time2-$time1.' s, do��czonych plik�w: '.count(get_included_files());
#var_dump(get_included_files());*/