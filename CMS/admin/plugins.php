<?php
if(iCMSa!=1 || !Admit('PI')) exit;
require LANG_DIR.'plugin.php';

#Pobierz zainstalowane
$setup = array();
include './cfg/plug.php'; 

#Tytu³
$content->title = $lang['plugs'];

#Instalacja
if(isset($_GET['setup']) && ctype_alnum($_GET['setup']))
{
	$name = $_GET['setup'];
	$data = parse_ini_file('./plugins/'.$name.'/plugin.ini');

	if(!isset($data['install']))
	{
		$content->info($lang['noinst']); //Nie wymaga instalacji
	}
	elseif($_POST)
	{
		include './lib/config.php';
		require './plugins/'.$name.'/setup.php';
		try
		{
			if(isset($setup[$name]))
			{
				unset($setup[$name]);
				Uninstall();
			}
			else
			{
				$setup[$name] = (float)$data['version'];
				Install();
			}
			$o = new Config('plug');
			$o -> add('setup', $setup);
			$o -> save();
		}
		catch(Exception $e)
		{
			$content->info($e); return 1;
		}
	}
	else
	{
		$opt = array();
		$i = 0;
		if(isset($data['o.'.$nlang]))
		{
			foreach(explode('*', $data['o.'.$nlang]) as $o)
			{
				$opt['o'.++$i] = Clean($o); //Opcje
			}
		}
		$content->data = array(
			'setup' => true,
			'www'   => isset($data['www']) ? Clean($data['www']) : null,
			'name'  => Clean($data[$nlang]),
			'ver'   => (float)$data['version'],
			'credits' => isset($data['credits']) ? Clean($data['credits']) : 'N/A',
			'options' => $opt
		);
		$content->info(isset($setup[$name]) ? $lang['warn2'] : $lang['warn']);
		return 1;
	}
}
else $content->info($lang['api']);

#Utwórz zmienne
$plugs = array();

#Niezainstalowane wtyczki
foreach(scandir('./plugins') as $plug)
{
	if($plug[0] == '.' OR !is_dir('./plugins/'.$plug)) continue;

	#Dane z pliku INI
	$data = parse_ini_file('./plugins/'.$plug.'/plugin.ini');

	#Do tablicy
	$plugs[] = array(
		'id'    => $plug,
		'ready' => isset($setup[$plug]) OR empty($data['install']),
		'name'  => isset($data[$nlang]) ? Clean($data[$nlang]) : $plug,
		'del'   => isset($setup[$plug]) ? '?a=plugins&amp;setup='.$plug : false,
		'config'=> isset($data['config'])
	);
}

#Do szablonu
$content->data = array('plugin' => &$plugs, 'setup' => false);