<?php /* Plik dla ¿±dañ JavaScript - XMLHTTPRequest */
define('iCMS',1);
require 'kernel.php';

#Gdy to nie jest ¿±danie JS lub brakuje modu³u
if(!JS OR !isset($_GET['co']) OR strpos($_GET['co'], '/') !== false OR isset($_GET['co'][30]))
{
	Header('Location: '.URL);
	exit;
}

#Szablon
$content->file = array($_GET['co']);

#Modu³ lub wtyczka
switch($_GET['co'])
{
	case 'preview': (include './lib/preview.php') or $content->set404(); break; //Podgl±d
	case 'comment': (include './mod/comment.php') or $content->set404(); break; //Dodaj komentarz
	case 'css': break;
	default:
		if(file_exists('./plugins/'.$_GET['co'].'/js.php'))
			(include './plugins/'.$_GET['co'].'/js.php') or $content->set404();
		else
			exit;
}

#Wy¶wietl szablon
$content->display();