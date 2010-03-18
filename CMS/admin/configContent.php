<?php
if(iCMSa!=1 || !admit('CFG')) exit;

#Zapisz
if($_POST)
{
	$opt =& $_POST;
	require './lib/config.php';
	try
	{
		#Tagi
		if(isset($_POST['tags']))
		{
			$cfg['tags'] = 1;
			$f = new Config('main');
			$f->var = 'cfg';
			$f->save($cfg);
			unset($opt['tags']);
		}
		elseif(isset($cfg['tags']))
		{
			unset($cfg['tags']);
			$f = new Config('main');
			$f->var = 'cfg';
			$f->save($cfg);
		}
		$f = new Config('content');
		$f->save($opt);
		$content->info($lang['saved']);
		include './admin/config.php';
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($lang['error'].$e);
	}
	unset($f);
}
else
{
	$opt =& $cfg;
}

require LANG_DIR.'admCfg.php';
require './cfg/content.php';

#Zmienna zawiera opcje <select>
$out = '<optgroup label="'.$lang['cats'].'">';

#Kategorie
$res = $db->query('SELECT ID,name FROM '.PRE.'cats WHERE sc=0 AND access!=3 ORDER BY name');
$res ->setFetchMode(3); //NUM

foreach($res as $cat)
{
	$out.='<option value="'.$cat[0].'">'.$cat[1].'</option>'; //Bez 1-
}
$res=null;

#Dla ka¿dego jêzyka
$i = 0;
$js = '';
$cats = array();

foreach(scandir('./lang') as $dir)
{
	if(strpos($dir,'.')===false && is_dir('./lang/'.$dir))
	{
		if(isset($cfg['start'][$dir]))
		{
			$js .= '$("df'.++$i.'").value="'.(float)$cfg['start'][$dir].'";';
		}
		$cats[strtoupper($dir)] = '<select name="start['.$dir.']" id="df'.$i.'">'.$out.'</select>';
	}
}

#Tytu³
$content->title = $lang['content'];

#Do szablonu
$content->data = array(
	'cfg' => &$opt,
	'js'  => &$js,
	'cats'=> &$cats
);