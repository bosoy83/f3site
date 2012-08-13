<?php
if(iCMSa!=1 || !admit('CFG')) exit;

#Get options
if($_POST) { $opt =& $_POST; } else { $opt =& $cfg; }

#Action: save
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

#Include options
include './cfg/account.php';
include './cfg/mail.php';

#Include language file
require LANG_DIR.'admCfg.php';

#Page title
$content->title = $lang['ua'];

#Template
$content->add('configUsers', array(
	'cfg'     => &$cfg,
	'mailBan' => join("\n", $cfg['mailban']),
	'nickBan' => join("\n", $cfg['nickban'])
));