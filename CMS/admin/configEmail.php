<?php
if(iCMSa!=1 || !admit('CFG')) exit;

#Get options
if($_POST) { $opt =& $_POST; } else { $opt =& $cfg; }

#Save
if($_POST)
{
	require './lib/config.php';
	$f = new Config('mail');
	try
	{
		$f->save($opt);
		$content->info($lang['saved']);
		event('CONFIG');
		include './admin/config.php';
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($lang['error'].$e);
	}
	$f = null;
}

include LANG_DIR.'admCfg.php';
include './cfg/mail.php';

#Template
$content->title = 'E-Mail';
$content->add('configEmail', array('cfg' => &$opt));