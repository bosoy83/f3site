<?php
if(iCMSa!=1 || !Admit('CDB')) exit;
require LANG_DIR.'adm_db.php';

#Funkcje
switch($db_db)
{
	case 'mysql':
		$type = 'mysql';
		$show = 'SHOW TABLES';
		break;
	case 'sqlite':
		$type = 'sqlite';
		$show = 'SELECT name FROM sqlite_master WHERE type="table" ORDER BY name';
		break;
	default:
		$content->info('Cannot parse database type.'); return;
}

#Tworzenie
if($_POST)
{
	$n="\n";
	@set_time_limit(50);

	#Typ + kompresja?
	if(isset($_POST['gz']))
	{
		header('Content-type: application/x-gzip'); $ex='.sql.gz';
	}
	else
	{
		header('Content-type: text/plain'); $ex='.sql';
	}
	header('Content-Disposition: attachment; filename='.
		str_replace( array('?','*',':','\\','/','<','>','|','"'),'',$_POST['name']).$ex);

	#Kompresja GZ?
	if(isset($_POST['gz'])) ob_start('ob_gzhandler'); else ob_start();

	#Dodaj gravis do nazw w³asnych (MySQL)
	if($type === 'mysql') $db->exec('SET SQL_QUOTE_SHOW_CREATE=1');

	#Nag³ówek - komentarz
	echo '#'.$cfg['title'].' - Data Backup ('.strftime('%Y-%m-%d').')'.$n;
	echo '#Database: '.$db_d.$n;
	echo '#----------'.$n.$n;

	#Tabele
	foreach($_POST['tab'] as $tab)
	{
		#Optymalizuj
		if($type === 'sqlite')
		{
			$db->exec('VACUUM '.$tab);
		}
		else
		{
			$db->exec('OPTIMIZE TABLE '.$tab);
		}

		#Tworzenie tabeli
		if(isset($_POST['create']))
		{
			echo '#Creating table '.$tab.$n;

			#Usuwanie?
			if(isset($_POST['del'])) echo 'DROP TABLE IF EXISTS `'.$tab.'`;'.$n;

			#Pobierz polecenie
			if($type === 'sqlite')
			{
				echo $db->query('SELECT sql FROM sqlite_master WHERE name="'.$tab.'"') -> fetchColumn() .';'.$n.$n;
			}
			else
			{
				$create = $db->query('SHOW CREATE TABLE '.$tab) -> fetch(3);
				echo $create[1].';'.$n.$n;
			}

			#Wypu¶æ dane
			ob_flush();
		}

		#Dane
		$all = $db->query('SELECT * FROM '.$tab);
		$all -> setFetchMode(3);
		echo '#Table data for '.$tab.$n;

		#Warto¶ci pól
		foreach($all as $row)
		{
			echo 'INSERT INTO `'.$tab.'` VALUES ('.join(',',array_map(array($db,'quote'),$row)).');'.$n;
			ob_flush(); //Zwolnij pamiêæ
		}
		unset($ile,$all,$row);
		echo $n;
		ob_flush(); //Zwolnij pamiêæ
	}

	#Zakoñcz
	ob_end_flush();
	exit;
}

#TABELE
$list = $db->query($show) -> fetchAll(7); //COLUMN
$tabs = '';

foreach($list as $tab)
{
	$tabs .= '<option>'.$tab.'</option>';
}

#FORM
$content->info($lang['dbInfo']);
$content->data = array(
	'tables' => $tabs,
	'name' => 'DB-'.strftime('%Y-%m-%d'),
	'gz' => function_exists('gzopen')
);