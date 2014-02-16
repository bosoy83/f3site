<?php
if(iCMSa!=1 || !admit('CFG')) exit;

if($_POST)
{
	$opt =& $_POST;
	$opt['title'] = clean($opt['title'],50);
	$opt['metaDesc'] = clean($opt['metaDesc']);
	$opt['antyFlood'] = (int)$opt['antyFlood'];
	$opt['pmLimit'] = (int)$opt['pmLimit'];
	$opt['commNum'] = (int)$opt['commNum'];
	$opt['pollRound'] = (int)$opt['pollRound'];
	$opt['RSS'] = empty($cfg['RSS']) ? array() : $cfg['RSS'];
	if(isset($cfg['tags'])) $opt['tags'] = 1;

	#API keys
	if($opt['captcha'] != 2)
	{
		if(empty($opt['pubKey'])) unset($opt['pubKey']);
		if(empty($opt['prvKey'])) unset($opt['prvKey']);
	}
	if($opt['captcha'] != 1)
	{
		if(empty($opt['sbKey'])) unset($opt['sbKey']);
	}

	require './lib/config.php';
	try
	{
		$f = new Config('main');
		$f->add('cfg', $opt);
		$f->save();
		$cfg = &$opt;
		$view->info($lang['saved']);
		event('CONFIG');
		include './admin/config.php';
		return 1;
	}
	catch(Exception $e)
	{
		$view->info($e);
	}
}
else
{
	$opt =& $cfg;
}

include LANG_DIR.'admCfg.php';

#Style
$skin = '';
foreach(scandir('style') as $x)
{
	if($x[0]!='.' && is_dir('style/'.$x) && file_exists('style/'.$x.'/body.html'))
	{
		$skin .= '<option'.($cfg['skin']==$x ? ' selected="selected"' : '').'>'.$x.'</option>';
	}
}

#API keys
if(empty($cfg['pubKey'])) $cfg['pubKey'] = '';
if(empty($cfg['prvKey'])) $cfg['prvKey'] = '';
if(empty($cfg['sbKey'])) $cfg['sbKey'] = '';

#Page title
$view->title = $lang['main'];

#Template
$view->add('configMain', array(
	'cfg' => &$opt,
	'skinlist' => &$skin,
	'langlist' => listBox('lang',1,$opt['lang']),
));
