<?php
#Do szablonu
$content->data = array(
	'intro'  => str_replace('%name', $cfg['title'], $lang['admintro'])
);

#Katalog INSTALL
if(LEVEL==4 && is_dir('install'))
{
	$content->info('<b style="color: red">'.$lang['warninst'].'</b>');
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