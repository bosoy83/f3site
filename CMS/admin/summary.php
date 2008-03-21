<?php
#Do szablonu
$content->data = array(
	'intro'  => str_replace('%name', $cfg['title'], $lang['admintro']),
	'warning'=> LEVEL==4 && is_dir('install') ? $lang['warninst'] : '',
);

#Kompiluj szablony
if(isset($_GET['compile']))
{
	include_once './lib/compiler.php';
	try
	{
		$comp = new Compiler;
		$comp -> debug = false; //Zmieñ na True, aby widzieæ komunikaty
		$comp -> examine();
		$comp -> src .= 'admin/';
		$comp -> cache .= 'admin/';
		$comp -> examine();
	}
	catch(Exception $e)
	{
		$content->message($e->getMessage());
	}
}
?>