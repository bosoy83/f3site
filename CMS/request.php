<?php /* Plik dla ¿±dañ JavaScript - XMLHTTPRequest */
define('REQUEST',1);
define('iCMS',1);
require 'kernel.php';

//Modu³
switch($_GET['co'])
{
	case 'preview': include('./lib/preview.php'); break; //Podgl±d
	case 'comm': include('./mod/comm.php'); break; //Dodaj komentarz

	//Wtyczka
	default:
		$co=Clean(str_replace('/','',$_GET['co']),20);

		if(file_exists('./plugins/'.$co.'/http.php'))
			include('./plugins/'.$co.'/http.php');
}