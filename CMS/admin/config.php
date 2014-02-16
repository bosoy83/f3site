<?php
if(iCMSa!=1 || !admit('CFG')) exit;

#Page title
$view->title = $lang['config'];

#Option categories language file
require LANG_DIR.'admCfg.php';

#Addons options
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

#Template
$view->css('style/admin/config.css');
$view->add('config', array(
	'plugins' => &$items,
	'censor' => url('censor', null, 'admin'),
	'latest' => url('configNew', null, 'admin'),
	'emots' => url('emots', null, 'admin'),
	'items' => url('configContent', null, 'admin'),
	'main'  => url('configMain', null, 'admin'),
	'email' => url('configEmail', null, 'admin'),
	'users' => url('configUsers', null, 'admin'),
	'rss'   => url('rss', null, 'admin'),
	'setup' => url('setup', null, 'admin')
));
