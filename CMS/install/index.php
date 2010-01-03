<?php
define('INSTALL',1);
header('Cache-Control: public');
header('Content-type: text/html; charset=utf-8');
header('X-Robots-Tag: noindex');

#Pełny adres URL
define('PATH', str_replace(array('//','install/'), array('/',''), dirname($_SERVER['PHP_SELF']).'/'));
define('URL', 'http://'.$_SERVER['SERVER_NAME'].PATH);

#Katalog roboczy + katalogi szablonów
chdir('../');
define('VIEW_DIR', './cache/install/');
define('SKIN_DIR', './install/HTML/');

#Klasy
require './lib/content.php';
require './lib/config.php';
require './install/install.php';

#Język
$setup = new Installer;
$error = array();

#Plik językowy
require './install/lang/'.$setup->lang.'.php';

#System szablonów
$content = new Content;
$content->title = $lang['installer'];

#System zainstalowany
if(file_exists('./cfg/db.php') && filesize('./cfg/db.php')>43) $content->message($lang['XX']);

#Sterowniki PDO
$dr = PDO::getAvailableDrivers();
$my = in_array('mysql', $dr);
$li = in_array('sqlite',$dr);

#Dostępna tylko 1 baza
$one = ($li xor $my) ? ($my ? 'mysql' : 'sqlite') : false;

#Sprawdź CHMOD-y
if(!$setup->chmods())
{
	$content->file = 'chmod';
	$content->data = array('chmod' => $setup->buildChmodTable());
}

#Etap instalacji
else switch(isset($_POST['stage']) ? $_POST['stage'] : ($one ? 1 : 0))
{
	#Operacje w bazie
	case 2:

	#Silnik SQL
	$type = isset($_POST['file']) ? 'sqlite' : 'mysql';

	#Dane POST
	$data = array(
		'type' => $type,
		'host' => $type=='mysql' ? htmlspecialchars($_POST['host']) : '',
		'db'   => $type=='mysql' ? htmlspecialchars($_POST['db']) : '',
		'user' => $type=='mysql' ? htmlspecialchars($_POST['user']) : '',
		'pass' => $type=='mysql' ? htmlspecialchars($_POST['pass']) : '',
		'file' => $type=='sqlite' ? htmlspecialchars($_POST['file']) : '',
		'skin' => htmlspecialchars($_POST['skin']),
		'title'=> htmlspecialchars($lang['myPage']),
		'pre'  => htmlspecialchars($_POST['pre']),
		'login'=> htmlspecialchars($_POST['login']),
		'samp' => isset($_POST['samp']),
		'urls' => (int)$_POST['urls'],
		'url'  => htmlspecialchars($_POST['url']),
		'path' => htmlspecialchars($_POST['path'])
	);

	#Prefix
	define('PRE', $data['pre']);
	if(!preg_match('/^[a-zA-Z0-9_]{0,9}$/', PRE))
	{
		$error[] = $lang['e1'];
	}

	#Hasło admina za krótkie lub nie pasuje
	if(!isset($_POST['uPass'][4]))
	{
		$error[] = $lang['e2'];
	}
	if($_POST['uPass'] != $_POST['uPass2'])
	{
		$error[] = $lang['e3'];
	}

	try
	{
		#Gdy są błędy
		if($error) throw new Exception('<ul><li>'.join('</li><li>',$error).'</li></ul>');

		#Operacje
		$setup->connect($data);
		$setup->sample = $data['samp'];
		$setup->title = $data['title'];
		$setup->urls = $data['urls'];
		$setup->loadSQL('./install/SQL/'.$type.'.sql');
		$setup->setupAllLang();
		$setup->admin($data['login'], $_POST['uPass']);
		$setup->commit($data);
		$content->data = null;
		$content->message($lang['done'], '..');
	}
	catch(Exception $e)
	{
		$content->info(nl2br($e->getMessage()));
		$content->file = 'form';
		$content->data = array(
			'data'  => &$data,
			'host'  => $_SERVER['HTTP_HOST'],
			'mysql' => $data['type'] == 'mysql',
			'skins' => $setup->getSkins($data['skin'])
		);
	}
	break;
	
	#Formularz
	case 1:

	$data = array(
		'host'  => $_SERVER['HTTP_HOST']=='localhost' ? 'localhost' : 'mysql.'.$_SERVER['HTTP_HOST'],
		'title' => $lang['myPage'],
		'urls'  => 3,
		'user'  => 'root',
		'pass'  => '',
		'db'    => '',
		'pre'   => 'f3_',
		'login' => 'Admin',
		'skin'  => 'default',
		'file'  => is_writable('..') ? '../db.db' : 'cfg/db.db',
		'samp'  => true,
		'url'   => URL,
		'path'  => PATH
	);

	$content->file = 'form';
	$content->data = array(
		'host'  => $_SERVER['HTTP_HOST'],
		'mysql' => $one=='mysql' || ($_POST && $_POST['type']=='mysql'),
		'data'  => &$data,
		'skins' => $setup->getSkins($data['skin'])
	);

	break;

	#Wybór bazy
	default: $content->file = 'start';
}

#Szablon główny
include $content->path('body');