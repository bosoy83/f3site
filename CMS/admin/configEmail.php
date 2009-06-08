<?php
if(iCMSa!=1 || !Admit('CFG')) exit;

#Dostêpne opcje
if($_POST) { $opt =& $_POST; } else { $opt =& $cfg; }

#Zapisz
if($_POST)
{
	require './lib/config.php';
	$f = new Config('mail');
	try
	{
		$f->save($opt);
		$content->info($lang['saved']);
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

#Do szablonu
$content->title = 'E-Mail';
$content->data['cfg'] =& $opt;