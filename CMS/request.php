<?php /* Plik dla ��da� JavaScript - XMLHTTPRequest */
define('iCMS',1);
require 'kernel.php';

#Gdy to nie jest ��danie JS
if(!JS) header('Location: '.URL);

#Gdy ID modu�u zawiera niedozwolone znaki
if(!isset($URL[0]) || strpos($URL[0], '/') !== false || isset($URL[0][30]))
{
	exit('Wrong URL params!');
}

#Modu� lub wtyczka
switch($URL[0])
{
	case 'preview': (include './lib/preview.php') or $content->set404(); break; //Podgl�d
	case 'css': $content->file = 'css'; break;
	default:
		if(file_exists('./plugins/'.$URL[0].'/js.php'))
			(include './plugins/'.$URL[0].'/js.php') or $content->set404();
		else
			exit;
}

#Wy�wietl szablon
if($content->file) $content->display();