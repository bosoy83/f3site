<?php
if(!$_POST) exit;

#Adres prowadz�cy
$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

#Ochrona przed CSRF
if($ref && strpos($ref, $_SERVER['HTTP_HOST']) === false) exit;

#J�dro
define('iCMS',1);
require './kernel.php';

#Adres IP
$ip = $db->quote($_SERVER['REMOTE_ADDR'].' '.
	((isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : ''));

#Oceny
if(isset($_POST['v']) && isset($_GET['type']) && $id && $_POST['v'] > 0 && $_POST['v'] < 6)
{
	/* Sprawdzi�, czy ocenianie w��czone !!!!!!!!!! */

	#Typy kategorii i ocena
	$data = parse_ini_file('cfg/types.ini', 1);
	$v = (int)$_POST['v'];
	$t = (int)$_GET['type'];

	#Zalogowany?
	if(LOGD!=1 && !isset($cfg['grate'])) $content->message(9, $ref);

	#Czy oceniany ID istnieje i jest w��czony
	if(!isset($data[$t]['rate']) OR !db_count($data[$t]['table'].' i INNER JOIN '.PRE.'cats c ON i.cat=c.ID WHERE i.access=1 AND c.access!=3 AND c.opt&4 AND i.ID='.$id))
	{
		$content->message(7, $ref);
	}

	#Co ocenia�?
	$rated = isset($_COOKIE['rated']) ? explode('o',$_COOKIE['rated']) : array();

	#Gdy ocenia� - zako�cz
	if(in_array($t.'.'.$id, $rated)) $content->message(6, $ref);

	#Je�eli brak wpisu w bazie, �e ocenia�...
	if(db_count('rates WHERE type='.$t.' AND ID='.$id.' AND IP='.$ip) === 0)
	{
		$db->beginTransaction();
		$db->exec('INSERT INTO '.PRE.'rates (type,ID,mark,IP) VALUES ('.$t.','.$id.','.$v.','.$ip.')');

		#Aktualizuj ocen�
		$num = $db->query('SELECT count(*),SUM(mark) FROM '.PRE.'rates WHERE type='.$t.' AND ID='.$id)->fetch(3);
		$avg = $num[0] > 0 ? round($num[1] / $num[0]) : 0;

		$db->exec('UPDATE '.PRE.$data[$t]['table'].' SET rate='.$avg.' WHERE ID='.$id);
		$db->commit();
	}

	#Zapisz cookie
	$rated[] = $t.'.'.$id;
	setcookie('rated', join('o',$rated), time()+7776000, $_SERVER['PHP_SELF']);

	#OK
	$content->message(5, $ref);
}

#Ankieta
if(isset($_POST['poll']))
{
	#Dane
	$poll = $db->query('SELECT * FROM '.PRE.'polls WHERE access="'
		.$nlang.'" ORDER BY ID DESC LIMIT 1') -> fetch(2);

	#Istnieje? + ID
	if($poll) { $id = $poll['ID']; } else { $content->message(22); exit; }

	#G�osowa� na...
	$voted = isset($_COOKIE['voted']) ? explode('o', $_COOKIE['voted']) : array();

	#ID u�ytkownika lub adres IP
	$u = ($poll['ison']==3 && LOGD==1) ? UID : $ip;

	#Mo�e g�osowa�?
	if(!in_array($poll['ID'],$voted) && $poll['ison']!=2 && (LOGD==1 || $poll['ison']==1))
	{
		#Je�eli brak wpisu w bazie, �e g�osowa�...
		if(db_count('pollvotes WHERE ID='.$id.' AND user='.$u)==0)
		{
			if($poll['type']==1)
			{
				$q = (int)$_POST['vote']; //1 odp.
			}
			else
			{
				$correct = array();
				foreach(array_keys($_POST['vote']) as $key)
				{
					if(is_numeric($key)) $correct[] = (int)$key; //Wiele odp.
				}
				$q = $correct ? implode(',',$correct) : 0;
			}
			#Aktualizuj
			try
			{
				$db->beginTransaction();
				$db->exec('UPDATE '.PRE.'polls SET num=num+1 WHERE ID='.$id);
				$db->exec('UPDATE '.PRE.'answers SET num=num+1 WHERE IDP='.$id.' AND ID IN ('.$q.')');
				$db->exec('INSERT INTO '.PRE.'pollvotes (user,ID) VALUES ('.$u.','.$id.')');
				$db->commit();

				#Pobierz odpowiedzi
				$o = $db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$id)->fetchAll(3);
				++$poll['num'];

				#Zapisz nowe dane do pliku
				require './lib/config.php';
				$file = new Config('./cache/poll_'.$nlang.'.php');
				$file->add('poll',$poll);
				$file->add('option',$o);
				$file->save();
			}
			catch(Exception $e)
			{
				$content->message(22); exit;
			}
		}
	}
	#Cookie
	$voted[] = $id;
	setcookie('voted', join('o',$voted), time()+7776000);
	
	#JS?
	if(isset($_GET['js']))
	{
		$_GET['id'] = $id; include 'mod/panels/poll.php'; //Wy�wietl ma�e wyniki
	}
	else
	{
		$content->message(5, MOD_REWRITE ? '/poll/'.$id : 'index.php?co=poll&amp;id='.$id);
	}
}