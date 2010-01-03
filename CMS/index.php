<?php //F3Site 3.0 - (C) 2009 COMPMaster - license in: LICENSE.TXT

#J±dro
define('iCMS',1);
require './kernel.php';

#£aduj modu³ - gdy nie istnieje lub u¿yto return, wy¶wietl stronê 404
if(isset($URL[0]) && !is_numeric($URL[0]) && strpos($URL[0],'/')===false && !isset($URL[0][30]))
{
	#Szablon
	$content->file = array($URL[0]);

	#Kolejno¶æ: modu³ wbudowany, rozszerzenie, 404
	if(file_exists('./mod/'.$URL[0].'.php'))
	{
		(include './mod/'.$URL[0].'.php') OR $content->set404();
	}
	elseif(file_exists('./plugins/'.$URL[0].'/default.php'))
	{
		(include './plugins/'.$URL[0].'/default.php') OR $content->set404();
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

#Gdy ¿±danie AJAX
if(JS)
{
	$content->display();
}
else
{
	#Kod HEAD
	if($cfg['head']) $content->head .= $cfg['head'];

	#Menu
	if(!file_exists('./cache/menu'.$nlang.'.php'))
	{
		include './lib/mcache.php';
		RenderMenu();
	}
	require './cache/menu'.$nlang.'.php';

	#Skórka
	include $content->path('body');
}