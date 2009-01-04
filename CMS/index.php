<?php
/* F3Site 3.0: copyright (C) 2009 COMPMaster
Skrypt rozpowszechniany na licencji GPL (wiêcej w license.txt i CZYTAJ.txt) */

#J±dro
define('iCMS',1);
require './kernel.php';

#£aduj modu³ - gdy nie istnieje lub u¿yto return, wy¶wietl stronê 404
if(isset($_GET['co']) && strpos($_GET['co'],'/')===false && !isset($_GET['co'][30]))
{
	#Szablon
	$content->file = array($_GET['co']);

	#Kolejno¶æ: modu³ wbudowany, rozszerzenie, 404
	if(file_exists('./mod/'.$_GET['co'].'.php'))
	{
		(include './mod/'.$_GET['co'].'.php') OR $content->set404();
	}
	elseif(file_exists('./plugins/'.$_GET['co'].'/default.php'))
	{
		(include './plugins/'.$_GET['co'].'/default.php') OR $content->set404();
	}
	else
	{
		$content->set404();
	}
}

#Kategoria
else
{
	include './cfg/content.php';
	(include './lib/category.php') OR $content->set404();
}

#Kod HEAD
if($cfg['head']) $content->head .= $cfg['head'];

#Menu
require './cache/menu'.$nlang.'.php';

#Skórka
include $content->path('body');
echo (xdebug_memory_usage()/1024).' KB (max: '.(xdebug_peak_memory_usage()/1024).' KB) ';