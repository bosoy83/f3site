<?php
define('iCMS',1);
require './kernel.php';
require LANG_DIR.'fileman.php';

/* Zabezpieczenia - upload na razie niedostêpny
if(isset($_SERVER['PHP_AUTH_PW']) && md5($_SERVER['PHP_AUTH_PW']) == $user[UID]['pass'])
{
	echo 'yes';
}
else
{
	header('HTTP/1.1 401 Unauthorized');
	header('WWW-Authenticate: Basic realm="'.$lang['repass'].'"');
	exit;
}

#Upload
if($_FILES)
{
	
}*/

#Uprawnienia
Admit('FM') or exit;
$admin = Admit('FM2');

#Aktualny katalog
if(isset($_GET['dir']) && strpos($_GET['dir'],'.')===false)
{
	$dir = $_GET['dir'];
	if(substr($dir,-1) != '/') $dir.='/';
}
else $dir='img/';

#Lista plików
$file = $folder = array();
$parent = ($dir=='/') ? false : '?dir='.join('/',explode('/',$dir,-2));

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
		$file[] = array('name' => $x, 'opt' => $admin, 'url' => $dir.$x);
	}
}
$_SESSION['FM'] = 1;

#Skróæ œcie¿kê do katalogu
if(isset($dir[50])) $dir = substr($dir, -40);

#Kompiluj szablon, gdy trzeba
$content->check && $content->compile('fileman.html');

#Szablon
include VIEW_DIR.'fileman.html';