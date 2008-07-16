<?php
if(iCMSa!=1 || !Admit('PI')) exit;
require LANG_DIR.'adm_pi.php';

#Pobierz zainstalowane
$setup = array();
include './cfg/plug.php';

#Tytu³ / INFO
$content->title = $lang['plugs'];
$content->info($lang['api_5'].'<p>Ability to installing and configuring plugins will be added soon.</p>');

#Utwórz zmienne
$used  = array();
$plugs = array();
$list  = '';

#Niezainstalowane wtyczki
foreach(scandir('./plugins') as $plug)
{
	if($plug[0] == '.' OR !is_dir('./plugins/'.$plug)) continue;

	#Dane z pliku INI
	$data = parse_ini_file('./plugins/'.$plug.'/plugin.ini');

	#Do tablicy
	$plugs[] = array(
		'id'    => $plug,
		'color' => isset($setup[$plug]) OR empty($data['install']) ? 'green' : '',
		'inst'  => isset($setup[$plug]) OR empty($data['install']) ? false : '?a=plugins&amp;setup='.$plug,
		'name'  => isset($data[$nlang]) ? $data[$nlang] : $plug,
		'del'   => isset($setup[$plug]) ? '?a=plugins&amp;del=1&amp;idp='.$plug : false,
		'config'=> empty($data['config']) ? false : ''
	);
}

#Do szablonu
$content->data = array(
	'plugin' => &$plugs,
	'list'   => &$list,
);