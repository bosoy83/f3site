<?php if(iCMSa!=1) exit;

#Aktualizuj notatnik
if($_POST && isset($_POST['notes']))
{
	if(!file_exists('./cfg/notes.txt'))
	{
		touch('./cfg/notes.txt');
		chmod('./cfg/notes.txt', 0600);
	}
	$notes = clean($_POST['notes'], 9999, 1);
	file_put_contents('./cfg/notes.txt', $notes, 2);
}
elseif(file_exists('./cfg/notes.txt'))
{
	$notes = file_get_contents('./cfg/notes.txt');
}
else
{
	$notes = '';
}

#Wersja
$ver = parse_ini_file('cfg/ver.ini');

#Do szablonu
$content->add('summary', array(
	'intro'  => sprintf($lang['admIntro'], $cfg['title']),
	'notes'  => $notes,
	'version'=> $ver['ver'],
	'server' => $_SERVER['SERVER_SOFTWARE'],
	'config' => url('configMain', '', 'admin'),
	'engine' => isset($db_u) ? 'MySQL' : 'SQLite'
));

#Katalog INSTALL
if(IS_OWNER && is_dir('install'))
{
	$content->info('<b>'.$lang['INSTALL'].'</b>', null, 'error');
}

#Kompiluj szablony
if(isset($_GET['compile']))
{
	include_once './lib/compiler.php';
	try
	{
		$comp = new Compiler;
		$comp->examine();
		$comp->src = SKIN_DIR;
		$comp->cache = VIEW_DIR;
		$comp->examine();
	}
	catch(Exception $e)
	{
		$content->message($e->getMessage());
	}
}