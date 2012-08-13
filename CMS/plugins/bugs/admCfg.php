<?php
if(iCMSa!=1 || !admit('CFG')) exit;

#Action: save
if($_POST)
{
	$opt =& $_POST;
	try
	{
		include './lib/config.php';
		$f = new Config('bugs');
		$f -> save($_POST);
		header('Location: '.URL.url('bugs', '', 'admin'));
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

#Template
$content->add('adminConfig', array('cfg' => &$opt));