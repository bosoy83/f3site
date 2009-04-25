<?php
if(iCMSa!=1 || !Admit('BUGADM')) exit;

#Jêzyk
if(file_exists('./plugins/bugs/lang/adm'.$nlang.'.php'))
{
	require './plugins/bugs/lang/adm'.$nlang.'.php';
}
else
{
	require './plugins/bugs/lang/en.php';
}

#Katalog szablonów
$content->dir = './plugins/bugs/style/';
$content->cache = './cache/bugs/';
$content->title = $lang['tracker'];

if(isset($_GET['act']))
{
	switch($_GET['act'])
	{
		case 's': require 'plugins/bugs/admSect.php'; break;
		case 'o': require 'plugins/bugs/admCfg.php'; break;
		case 'e': require 'plugins/bugs/admEdit.php'; break;
		default: return;
	}
}
else
{
	require 'plugins/bugs/admcats.php';
}