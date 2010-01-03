<?php
if(iCMSa!=1 || !admit('CFG')) exit;

#Aktualizuj cache menu
if(isset($_SESSION['renew']))
{
	try
	{
		require './lib/mcache.php';
		require './lib/categories.php';
		RenderMenu();
		Latest();
		RSS();
		if(function_exists('glob') && $glob = glob('cache/cat*.php'))
		{
			foreach($glob as $x) unlink($x); #Wyczy�� cache kategorii
		}
		unset($_SESSION['renew'],$glob,$x);
		include './admin/config.php';
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($lang['saved']);
	}
}

#Dost�pne opcje
if($_POST) { $opt =& $_POST; } else { $opt =& $cfg; }

#Zapisz
if($_POST)
{
	$opt['title'] = clean($opt['title'],50);
	$opt['metaDesc'] = clean($opt['metaDesc']);
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
		if($cfg['niceURL'] != $opt['niceURL'])
		{
			$_SESSION['admenu'] = null;
			$_SESSION['renew'] = 1;
			$content->message(19, url('configMain','renew','admin'));
		}
		$cfg = &$opt;
		$content->info($lang['saved']);
		include './admin/config.php';
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($e);
	}
}

include LANG_DIR.'admCfg.php';

#Sk�rki
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
	'langlist' => listBox('lang', 1, $opt['lang']),
);