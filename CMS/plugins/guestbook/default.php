<?php
if(iCMS!=1) exit;

#Konfiguracja
require './cfg/gb.php';

#Jêzyk
if(file_exists('./plugins/guestbook/lang/'.$nlang.'.php'))
{
	require './plugins/guestbook/lang/'.$nlang.'.php';
}
else
{
	require './plugins/guestbook/lang/en.php';
}

#Szablony
$content->dir = './plugins/guestbook/style/';
$content->cache = './cache/guestbook/';
$content->file = $cfg['gbSkin'];

#Ksiêga wy³¹czona
if(!isset($cfg['gbOn']))
{
	$content->info($lang['disabled']);
	return 1;
}

#Akcja
if(isset($_GET['act']))
{
	switch($_GET['act'])
	{
		case 'post': require './plugins/guestbook/post.php'; break;
		default: return;
	}
}
else
{
	require './plugins/guestbook/list.php';
}