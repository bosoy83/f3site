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
	'censor' => url('censor', '', 'admin'),
	'latest' => url('configNew', '', 'admin'),
	'emots' => url('emots', '', 'admin'),
	'items' => url('configContent', '', 'admin'),
	'main'  => url('configMain', '', 'admin'),
	'email' => url('configEmail', '', 'admin'),
	'users' => url('configUsers', '', 'admin'),
);
