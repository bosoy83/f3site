<?php
if(iCMS!=1) exit;

#Konfiguracja
require './cfg/gb.php';

#Język
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

#Księga wyłączona
if(!isset($cfg['gbOn']))
{
	$content->info($lang['disabled']);
	return 1;
}

#Akcja
if(isset($URL[1]))
{
	switch($URL[1])
	{
		case 'post': require './plugins/guestbook/post.php'; break;
		default: return;
	}
}
else
{
	require './plugins/guestbook/list.php';
}