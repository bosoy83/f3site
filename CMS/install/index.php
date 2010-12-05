<?php
header('Cache-Control: public');
header('Content-Type: text/html; charset=utf-8');
header('X-Robots-Tag: noindex');

#Full URL
define('PATH', str_replace(array('//','install/'), array('/',''), dirname($_SERVER['PHP_SELF']).'/'));
define('URL', 'http://'.$_SERVER['SERVER_NAME'].PATH);

#Working folder
chdir('../');
define('VIEW_DIR', './cache/install/');
define('SKIN_DIR', './install/HTML/');

#Classes
require './lib/content.php';
require './lib/config.php';
require './install/install.php';

#Lang
$setup = new Installer;
$error = array();

#Lang file
require './install/lang/'.$setup->lang.'.php';

#Templates
$content = new Content;
$content->title = $lang['installer'];

#Already done
if(file_exists('./cfg/db.php') && filesize('./cfg/db.php')>43) $content->message($lang['ban']);

#PDO drivers
$dr = PDO::getAvailableDrivers();
$my = in_array('mysql', $dr);
$li = in_array('sqlite',$dr);

#No driver
if(!$my && !$li) $content->message($lang['noDB']);

#Only one
$one = ($li xor $my) ? ($my ? 'mysql' : 'sqlite') : false;

#Check CHMODs
if(!$setup->chmods())
{
	$content->file = 'chmod';
	$content->data = array('chmod' => $setup->buildChmodTable());
}

#Installer level
else switch(isset($_POST['stage']) ? $_POST['stage'] : ($one ? 1 : 0))
{
	#Install
	case 2:

	$type = isset($_POST['file']) ? 'sqlite' : 'mysql';
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
		'mail' => filter_input(INPUT_POST, 'mail', 274),
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

	#Wrong password
	if(!isset($_POST['uPass'][4]))
	{
		$error[] = $lang['e2'];
	}
	if($_POST['uPass'] != $_POST['uPass2'])
	{
		$error[] = $lang['e3'];
	}

	#Bad mail
	if(empty($data['mail']))
	{
		$error[] = $lang['e4'];
	}

	try
	{
		#Errors
		if($error) throw new Exception('<ul><li>'.join('</li><li>',$error).'</li></ul>');

		#Begin
		$setup->connect($data);
		$setup->sample = $data['samp'];
		$setup->title = $data['title'];
		$setup->urls = $data['urls'];
		$setup->loadSQL('./install/SQL/'.$type.'.sql');
		$setup->setupAllLang();
		$setup->admin($data['login'], $_POST['uPass']);
		$setup->commit($data);
		$content->data = null;
		$content->message($lang['OK'], '..');
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
	
	#Form
	case 1:

	$data = array(
		'host'  => 'localhost',
		'title' => $lang['myPage'],
		'urls'  => $setup->urls(),
		'user'  => 'root',
		'pass'  => '',
		'db'    => '',
		'pre'   => 'f3_',
		'login' => 'Admin',
		'mail'  => '@',
		'skin'  => 'system',
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

	#Select database
	default: $content->file = 'start';
}

#Main template
include $content->path('body');