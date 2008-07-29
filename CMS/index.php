<?php
/* F3Site 3.0: copyright (C) 2007 COMPMaster
Skrypt rozpowszechniany na licencji GPL (wiêcej w license.txt i CZYTAJ.txt) */

#J±dro
define('iCMS',1);
require './kernel.php';

#£aduj modu³ - gdy nie istnieje lub u¿yto return, wy¶wietl stronê 404
if(isset($_GET['co']) && strpos($_GET['co'],'/')===false && !isset($_GET['co'][30]))
{
	#Szablon
	$content->file = array($_GET['co']);

	if(file_exists('./mod/'.$_GET['co'].'.php'))
	{
		(include './mod/'.$_GET['co'].'.php') OR $content->set404(); #Modu³?
	}
	elseif(file_exists('./plugins/'.$_GET['co'].'/default.php'))
	{
		(include './plugins/'.$_GET['co'].'/default.php') OR $content->set404(); #Wtyczka?
	}
	else
	{
		$content->set404(); #B³±d 404 - strona nie istnieje
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

#Kompiluj skórkê, gdy potrzeba...
if($content->check && filemtime(SKIN_DIR.'body.html') > @filemtime(VIEW_DIR.'body.html'))
{
	$content->compile('body.html');
}

#Skórka
include VIEW_DIR.'body.html';
#echo 'U¿ycie pamiêci: '.(xdebug_memory_usage()/1024).' KB, Max: '.(xdebug_peak_memory_usage()/1024),' KB';