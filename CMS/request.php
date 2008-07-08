<?php /* Plik dla ��da� JavaScript - XMLHTTPRequest */
define('JS',1);
define('iCMS',1);
require 'kernel.php';

#Brak modu�u / zawiera uko�nik / za d�ugi?
if(!isset($_GET['co']) OR strpos($_GET['co'], '/') !== false OR isset($_GET['co'][30])) exit;

#Szablon
$content->file = array($_GET['co']);

#Modu� lub wtyczka
switch($_GET['co'])
{
	case 'preview': (include './lib/preview.php') or $content->set404(); break; //Podgl�d
	case 'comment': (include './mod/comment.php') or $content->set404(); break; //Dodaj komentarz
	case 'css': break;
	default:
		if(file_exists('./plugins/'.$co.'/js.php'))
			(include './plugins/'.$co.'/js.php') or $content->set404();
		else
			exit;
}

#Wy�wietl szablon
$content->display();