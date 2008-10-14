<?php
/* F3Site 3.0: copyright (C) 2007 COMPMaster
Skrypt rozpowszechniany na licencji GPL (wi�cej w license.txt i CZYTAJ.txt) */

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
		(include './mod/'.$_GET['co'].'.php') OR $content->set404(); #Modu�?
	}
	elseif(file_exists('./plugins/'.$_GET['co'].'/default.php'))
	{
		(include './plugins/'.$_GET['co'].'/default.php') OR $content->set404(); #Wtyczka?
	}
	else
	{
		$content->set404(); #B��d 404 - strona nie istnieje
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

#Kompiluj sk�rk�, gdy potrzeba...
$content->check && $content->compile('body.html');

#Sk�rka
include VIEW_DIR.'body.html';