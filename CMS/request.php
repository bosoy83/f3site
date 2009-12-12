<?php /* Plik dla ¿±dañ JavaScript - XMLHTTPRequest */
define('iCMS',1);
require 'kernel.php';

#Gdy to nie jest ¿±danie JS lub brakuje modu³u
if(!JS || !isset($_GET['co']) || strpos($_GET['co'], '/') !== false || isset($_GET['co'][30]))
{
	header('Location: '.URL);
	exit;
}

#Modu³ lub wtyczka
switch($_GET['co'])
{
	case 'preview': (include './lib/preview.php') or $content->set404(); break; //Podgl±d
	case 'css': $content->file = 'css'; break;
	case 'comment': (include './mod/comment.php') or $content->set404(); break; //Dodaj komentarz
	default:
		if(file_exists('./plugins/'.$_GET['co'].'/js.php'))
			(include './plugins/'.$_GET['co'].'/js.php') or $content->set404();
		else
			exit;
}

#Wy¶wietl szablon
if($content->file) $content->display();