<?php
if(iCMSa!=1 || !Admit('CFG')) exit;

#Dostêpne opcje
if($_POST) { $opt =& $_POST; } else { $opt =& $cfg; }

#Zapisz
if($_POST)
{
	$opt['mailban'] = empty($opt['mailban']) ? array() : explode("\n",$opt['mailban']);
	$opt['nickban'] = empty($opt['nickban']) ? array() : explode("\n",$opt['nickban']);

	require './lib/config.php';
	try
	{
		$f = new Config('account');
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

#Opcje
include './cfg/account.php';
include './cfg/mail.php';

#Jêzyk
require LANG_DIR.'adm_cfgm.php';

#Tytu³ strony
$content->title = $lang['config'];

#Do szablonu
$content->data = array(
	'cfg' => &$cfg,
	'mailBan' => join("\n", $cfg['mailban']),
	'nickBan' => join("\n", $cfg['nickban'])
);