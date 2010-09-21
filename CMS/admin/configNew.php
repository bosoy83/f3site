<?php
if(iCMSa!=1 || !admit('CFG')) exit;

#Dostêpne opcje
if($_POST) { $opt =& $_POST; } else { $opt =& $cfg; }

#Typy kategorii
$type = array();
$data = parse_ini_file('./cfg/types.ini', 1);

#Zapis
if($_POST)
{
	#Typy danych musz¹ byæ numeryczne
	if(isset($_POST['newTypes']))
	{
		foreach($_POST['newTypes'] as &$x) $x = (int)$x;
	}
	try
	{
		include './lib/config.php';
		$f = new Config('latest');
		$f -> save($_POST);

		#Aktualizuj liste
		include './lib/categories.php';
		Latest();

		#Powrot do menu opcji
		include './admin/config.php';
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($lang['error'].$e);
	}
}
else
{
	require './cfg/latest.php';
}
require LANG_DIR.'admCfg.php';

foreach($data as $num => &$x)
{
	$type[] = array(
		'id' => $num,
		'on' => isset($opt['newTypes'][$num]),
		'title' => isset($x[LANG]) ? $x[LANG] : $x['en']
	);
}

$content->title = $lang['latest'];
$content->data = array('cfg'=>$opt, 'type'=>$type);