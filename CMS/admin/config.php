<?php
if(iCMSa!=1 || !admit('CFG')) exit;

#Tytu³
$content->title = $lang['config'];

#Lista dzia³ów opcji
require LANG_DIR.'admCfg.php';

#Odczyt opcji wtyczek
$items = array();
$res = $db->query('SELECT ID,name,img FROM '.PRE.'confmenu WHERE lang=1 OR lang="'.LANG.'"');
foreach($res as $x)
{
	$items[] = array(
		'name' => $x['name'],
		'img'  => $x['img'],
		'url'  => url($x['ID'], '', 'admin')
	);
}

#Do szablonu
$content->file = 'config';
$content->addCSS('style/admin/config.css');
$content->data = array(
	'plugins' => &$items,
	'censor' => url('censor', null, 'admin'),
	'latest' => url('configNew', null, 'admin'),
	'emots' => url('emots', null, 'admin'),
	'items' => url('configContent', null, 'admin'),
	'main'  => url('configMain', null, 'admin'),
	'email' => url('configEmail', null, 'admin'),
	'users' => url('configUsers', null, 'admin')
);