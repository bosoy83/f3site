<?php
define('iCMS',1);
require './kernel.php';
require LANG_DIR.'fileman.php';

#Rozszerzenia, ktorych nie mo¿na wyœwietliæ
$banEx = array('.php'=>1, '.db'=>1, '.ini'=>1);

#Uprawnienia
Admit('FM') or exit;
$admin = Admit('UP');

#Aktualny katalog
if(isset($_GET['dir']) && strpos($_GET['dir'],'.')===false)
{
	$dir = $_GET['dir'];
	if(substr($dir,-1) != '/' && $dir != '') $dir.='/';
}
else $dir='img/';

#Lista plików
$file = $folder = array();
$parent = $dir ? '?dir='.join('/',explode('/',$dir,-2)) : false;

foreach(scandir('./'.$dir) as $x)
{
	if($x[0] === '.') 
	{
		continue;
	}
	if(is_dir('./'.$dir.$x))
	{
		$folder[] = array('name' => $x, 'opt' => $admin, 'url' => '?dir='.$dir.$x);
	}
	else
	{
		$e = strrchr($x, '.');
		$file[] = array('name' => $x, 'opt' => $admin, 'url' => isset($banEx[$e]) ? '#' : $dir.$x);
	}
}
$_SESSION['FM'] = 1;

#Skróæ œcie¿kê do katalogu
if(isset($dir[50])) $dir = substr($dir, -40);

#Szablon
include $content->path('fileman');