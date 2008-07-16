<?php
if(iCMSa!=1 || !Admit('CFG')) exit;

#Dostêpne opcje
if($_POST) { $opt =& $_POST; } else { $opt =& $cfg; }

#Zapisz
if($_POST)
{
	require './lib/config.php';
	$f = new Config('content');
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

require LANG_DIR.'adm_cfgz.php';
require 'cfg/content.php';

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
		$js .= 'id("df'.++$i.'").value="'.(float)$cfg['start'][$dir].'";';
		$cats[strtoupper($dir)] = '<select name="start['.$dir.']" id="df'.$i.'">'.$out.'</select>';
	}
}

#Do szablonu
$content->data = array(
	'cfg' => &$opt,
	'js'  => &$js,
	'cats'=> &$cats
);