<?php
define('INSTALL',1);
Header('Cache-Control: public');
Header('Content-type: text/html; charset=utf-8');

#Zmień katalog roboczy i ustaw katalogi szablonów
chdir('../');
define('VIEW_DIR', './cache/default/');
define('SKIN_DIR', './style/default/');
require './lib/content.php';

#Obiekty: szablony + setup
$content = new Content;
$content -> dir = './install/HTML/';
$content -> cache = './cache/install/';

#Język
$nlang = 'en';
$error = array();

foreach(explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']) as $x)
{
	if(isset($x[2]))
	{
		$x = $x[0].$x[1];
	}
	if(ctype_alnum($x) && file_exists('./install/lang/'.$x.'.php'))
	{
		$nlang = $x; break;
	}
}
unset($x);
require './install/lang/'.$nlang.'.php';

#Skompiluj najważniejsze pliki szablonu
if(!file_exists('./cache/default/info.php'))
{
	include_once './lib/compiler.php';
	try
	{
		$c = new Compiler();
		$c -> compile('info.html');
		$c -> compile('message.html');
		$c -> compile('body.html');
	}
	catch(Exception $e)
	{
		echo $lang['TP'];
	}
	unset($c);
}

#System zainstalowany
if(file_exists('./cfg/db.php') && filesize('./cfg/db.php')>43) $content->message($lang['XX']);

#Sterowniki PDO
$dr = PDO::getAvailableDrivers();
$my = in_array('mysql', $dr);
$li = in_array('sqlite',$dr);

#FORM + INSTALUJ
if($_POST OR isset($_GET['next']))
{
	if($_POST)
	{
		#Doł±cz klasę zapisu do .php
		require './lib/config.php';
		require './install/install.php';

		#TYP
		$type = ($_POST['type']=='sqlite' OR $_POST['type']=='mysql') ? $_POST['type'] : null;

		#Dane dostępowe
		$data = array(
			'type' => $type,
			'host' => $type=='mysql' ? htmlspecialchars($_POST['host']) : '',
			'db'   => $type=='mysql' ? htmlspecialchars($_POST['db']) : '',
			'user' => $type=='mysql' ? htmlspecialchars($_POST['user']) : '',
			'pass' => $type=='mysql' ? htmlspecialchars($_POST['pass']) : '',
			'file' => $type=='sqlite' ? htmlspecialchars($_POST['file']) : '',
			'pre'  => htmlspecialchars($_POST['pre']),
			'login'=> htmlspecialchars($_POST['login'])
		);

		#Prefix
		if(!preg_match('/^[a-zA-Z0-9_]{0,9}$/', $data['pre']))
		{
			$error[] = $lang['e1'];
		}

		#Hasło admina
		if(!preg_match('/^[a-zA-Z0-9_-]{5,20}$/', $_POST['uPass']))
		{
			$error[] = $lang['e2'];
		}

		#Hasło admina
		if($_POST['uPass'] != $_POST['uPass2'])
		{
			$error[] = $lang['e3'];
		}

		#Prefix do stałej
		define('PRE', $data['pre']);
		try
		{
			#Gdy s± błędy
			if($error) throw new Exception('<ul><li>'.join('</li><li>',$error).'</li></ul>');

			#API instalatora - przekaż domyślny język i dane
			$setup = new Installer($nlang, $data);

			#Załaduj plik SQL
			$setup -> loadSQL('./install/SQL/'.$type.'.sql');

			#Instaluj zawartość dla każdego języka
			foreach(scandir('./lang') as $dir)
			{
				if($dir[0]!='.' && file_exists('./install/lang/'.$dir.'.php')) $setup -> setupLang($dir);
			}

			#Dodaj admina
			$setup -> admin($data['login'], $_POST['uPass']);

			#Konfiguracja
			$f = new Config('./cfg/db.php');
			$f -> add('db_db', $type);
			$f -> add('db_d', $data['file'] ? $data['file'] : $data['db']);
			$f -> addConst('PRE', PRE);

			#Tylko dla MySQL
			if($type == 'mysql')
			{
				$f -> add('db_h', $data['host']);
				$f -> add('db_u', $data['user']);
				$f -> add('db_p', $data['pass']);
			}
			$f -> save();

			#Menu cache
			$db = $setup->db;
			include './lib/mcache.php';
			RenderMenu();

			#Aktualne Sondy
			if(file_exists('./mod/polls'))
			{
				include './mod/polls/poll.php';
				RebuildPoll();
			}

			#Zakończ instalację
			$setup -> commit();
			$content -> data = null;
			$content -> info($lang['OK'], array('../.' => $lang[0]));
			include $content->path('body');
			exit;
		}
		catch(Exception $e)
		{
			$content->info(nl2br($e->getMessage()));
		}
	}
	else
	{
		$data = array(
			'type' => $my ? 'mysql' : 'sqlite',
			'host' => 'localhost',
			'user' => 'root',
			'pass' => '',
			'db'   => '',
			'file' => (file_exists('../htdocs/../') && is_writable('../')) ? '../db.db' : './cfg/db.db',
			'pre'  => 'f3_',
			'login'=> 'Admin'
		);
	}

	#Szablon
	$content->file = 'form';
	$content->data = array('data' => $data, 'host' => $_SERVER['HTTP_HOST']);
}

#START
else
{
	#Wersja PHP
	$php = (PHP_VERSION >= 5.2) ? PHP_VERSION : '<span>'.PHP_VERSION.'</span>';

	#PDO
	if(extension_loaded('pdo'))
	{
		if($li && $my)
			$pdo = 'MySQL + SQLite';
		elseif($my)
			$pdo = 'MySQL';
		elseif($li)
			$pdo = 'SQLite';
		else
		{
			$pdo = '<span>- - -</span>';
			$error[] = $lang['e4'];
		}
	}
	else
	{
		$pdo = '<span>- - - -</span>';
		$error[] = $lang['e5'];
	}

	#RegisterGlobals
	$rg = ini_get('register_globals') ? '<span>ON</span>' : 'OFF';

	#MagicQuotes
	$mq = ini_get('magic_quotes_gpc') ? '<span>ON</span>' : 'OFF';

	#CHMOD
	if(is_writable('./cache') && is_writable('./cfg') && is_writable('./cache/default'))
	{
		$ch = 'OK';
	}
	else
	{
		$ch = '<span>cfg + cache</span>';
		$error[] = $lang['e6'];
	}

	#Błędy
	if($error) $content->info('<ul><li>'.join('</li><li>',$error).'</li></ul>');

	#Do szablonu
	$content->file = 'start';
	$content->data = array(
		'php' => $php,
		'pdo' => $pdo,
		'rg'  => $rg,
		'mq'  => $mq,
		'next' => empty($error),
		'chmod' => $ch,
	);
}

#Szablon główny
include $content->path('body');