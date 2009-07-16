<?php
if(iCMSa!=1 || !Admit('CFG')) exit;

#Dostêpne opcje
if($_POST) { $opt =& $_POST; } else { $opt =& $cfg; }

#Zapisz
if($_POST)
{
	$opt['title'] = Clean($opt['title'],50);
	$opt['metaDesc'] = Clean($opt['metaDesc']);
	$opt['antyFlood'] = (int)$opt['antyFlood'];
	$opt['pmLimit'] = (int)$opt['pmLimit'];
	$opt['commNum'] = (int)$opt['commNum'];
	$opt['pollRound'] = (int)$opt['pollRound'];

	require './lib/config.php';
	$f = new Config('main');
	$f ->add('cfg', $opt);
	try
	{
		$f->save();
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

#Skórki
$skin = '';
foreach(scandir('style') as $x)
{
	if($x[0] != '.' && is_dir('style/'.$x) && file_exists('style/'.$x.'/1.css'))
	{
		$skin .= '<option'.($cfg['skin']==$x ? ' selected="selected"' : '').'>'.$x.'</option>';
	}
}

$content->title = $lang['main'];
$content->data = array(
	'cfg' => &$opt,
	'skinlist' => &$skin,
	'langlist' => ListBox('lang', 1, $opt['lang']),
);