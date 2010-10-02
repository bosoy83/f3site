<?php
if(iCMSa!=1 || !admit('GB')) exit;

#Konfiguracja
require './cfg/gb.php';

#Język
if(file_exists('./plugins/guestbook/lang/'.LANG.'.php'))
{
	require './plugins/guestbook/lang/'.LANG.'.php';
}
else
{
	require './plugins/guestbook/lang/en.php';
}

#Szablony
$content->dir = './plugins/guestbook/style/';
$content->cache = './cache/guestbook/';
$content->file = 'admin';
$content->title = $lang['gbAdmin'];

#Usuń stare
if(isset($_POST['prune']) && strlen($_POST['prune'])===20)
{
	$db->prepare('DELETE FROM '.PRE.'guestbook WHERE date<?')->execute(array(strtotime($_POST['prune'])));
	$content->info($lang['gbPruned']);
}

$styles = '';
$opt = null;

#Ustawienia
if(admit('CFG'))
{
	#Zapis
	if(isset($_POST['gbSkin']))
	{
		$opt =& $_POST;
		$opt['gbSkin'] = str_replace(array('.', '/', '\\'), '', $opt['gbSkin']);
		require './lib/config.php';
		$f = new Config('gb');
		try
		{
			$f->save($opt);
			$content->info($lang['saved']);
		}
		catch(Exception $e)
		{
			$content->info($lang['error'].$e);
		}
	}
	else
	{
		$opt =& $cfg;
	}
	#Style
	foreach(scandir('./plugins/guestbook/style') as $x)
	{
		if(strpos($x,'.html') && $x != 'admin.html' && $x != 'index.html' && $x != 'posting.html')
		{
			$styles .= '<option>'.substr($x, 0, -5).'</option>';
		}
	}
}

#Dane do szablonu 
$content->data = array(
	'cfg'    => &$opt,
	'styles' => $styles,
);