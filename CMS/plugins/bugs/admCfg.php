<?php
if(iCMSa!=1 OR !admit('CFG')) exit;

#Zapis
if($_POST)
{
	$opt =& $_POST;
	try
	{
		include './lib/config.php';
		$f = new Config('bugs');
		$f -> save($_POST);
		header('Location: '.URL.url('bugs'));
		$content->message($lang['saved'], url('bugs', '', 'admin'));
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
$content->data = array('cfg'  => &$opt);