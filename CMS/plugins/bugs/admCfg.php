<?php
if(iCMSa!=1 OR !Admit('CFG')) exit;

#Zapis
if($_POST)
{
	$opt =& $_POST;
	try
	{
		include './lib/config.php';
		$f = new Config('bugs');
		$f -> save($_POST);
		Header('Location: '.URL.'adm.php?a=bugs');
		$content->message($lang['saved'], '?a=bugs');
	}
	catch(Exception $e)
	{
		$content->info($lang['error'].$e);
	}
}
else
{
	$opt =& $cfg;
	require './cfg/bugs.php';
}

#Szablony
$content->file = 'adminConfig';
$content->data = array(
	'cfg'  => &$opt
);