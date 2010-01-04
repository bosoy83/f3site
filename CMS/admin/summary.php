<?php if(iCMSa!=1) exit;

#Do szablonu
$content->data = array(
	'intro'  => sprintf($lang['admIntro'], $cfg['title']),
	'system' => isset($_ENV['OS']) ? $_ENV['OS'] : 'N/A',
	'server' => $_SERVER['SERVER_SOFTWARE'],
	'course' => LANG == 'pl' ? 'http://webmaster.helion.pl' : 'http://www.w3schools.com/html'
);

#Katalog INSTALL
if(LEVEL==4 && is_dir('install'))
{
	$content->info('<b style="color: red">'.$lang['INSTALL'].'</b>');
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
