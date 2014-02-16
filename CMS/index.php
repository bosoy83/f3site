<?php //F3Site 2013 (C) COMPMaster
define('iCMS',1);
require './kernel.php';

#Maintenance mode
if(isset($cfg['MA']) && !IS_EDITOR)
{
	header('Service Unavailable', true, 503);
	header('Retry-After: 7200');
	$view->message(10, UID ? null : 'login.php');
}

#Default META description
$view->desc = $cfg['metaDesc'];

#Load module: built-in module, extension, 404
if(isset($URL[0]) && !is_numeric($URL[0]) && strpos($URL[0],'/')===false && !isset($URL[0][30]))
{
	if(file_exists('./mod/'.$URL[0].'.php'))
	{
		include './mod/'.$URL[0].'.php';
	}
	elseif(file_exists('./plugins/'.$URL[0].'/default.php'))
	{
		include './plugins/'.$URL[0].'/default.php';
	}
}

#Category
else
{
	include './lib/category.php';
}

#AJAX
if(JS)
{
	$view->display();
}
else
{
	#Menu
	if(!file_exists('./cache/menu'.LANG.'.php'))
	{
		include './lib/mcache.php';
		RenderMenu();
	}
	require './cache/menu'.LANG.'.php';

	#Channels for language
	if(!empty($cfg['RSS'][LANG]))
	{
		$view->rss($cfg['RSS'][LANG]);
	}

	#Main template
	$view->front();
}
