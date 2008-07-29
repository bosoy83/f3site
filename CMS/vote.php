<?php
if(!$_POST) exit;
define('iCMS',1);
require './kernel.php';
echo 'Polls will be fixed and enhanced in next revision...';
include './mod/polls/poll.php';
RebuildPoll();
exit;

#Adres IP
$ip = $db->quote($_SERVER['REMOTE_ADDR'].' '.
	((isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : ''));

#Oceny
/* Na razie wy³±czone
if(is_numeric($_GET['t']))
{
	#Opcje
	require_once('./cfg/content.php');
	$c=$_COOKIE[PRE.'rates'];

	if(LOGD!=1 && $cfg['grate']!=1) exit('Error: access denied.');

	#Tabela (nie usuwaj!)
	$table='';
	switch($_GET['t'])
	{
		case 1: if($cfg['arate']==1) { $table='art'; } break;
		case 2: if($cfg['frate']==1) { $table='file'; } break;
		case 3: if($cfg['irate']==1) { $table='img'; } break;
	}

	#B³±d?
	if($table=='')
	{
	exit('Error: rating disabled.');
	}
	#Ocenia³?
	if(strpos($c,'x'.$_GET['t'].':'.$id.'x')!==false)
	{
	$content->message(6);
	}
	else
	{
	#ID/IP
	if(LOGD==1)
	{
	 $usr=UID;
	}
	else
	{
	 $usr='"'.db_esc($_SERVER['REMOTE_ADDR'].' '.$_SERVER['HTTP_X_FORWARDED_FOR']).'"';
	}

	#Jest blokada?
	if(db_count('ID',$table.'rates WHERE ID='.$id.' AND user='.$usr)==0)
	{
	 $new=1;
	}
	else { $new=0; }

	#Zapis
	if($_POST['v'])
	{
	 //ISNUMERIC
	 
	}

	#Formularz
	else
	{
	 echo '<form action="vote.php?id='.$id.'&amp;t='.$_GET['t'].'" method="post">
	 <input type="radio" name="v" value="5" /> '.$lang['vgood'].'
	 <input type="radio" name="v" value="4" /> '.$lang['good'].'
	 <input type="radio" name="v" value="3" /> '.$lang['dstg'].'
	 <input type="radio" name="v" value="2" /> '.$lang['weak'].'
	 <input type="radio" name="v" value="1" /> '.$lang['fatal'].'
	 </form>';
	}
	}
	}
*/

#Ankieta
if(isset($_POST['poll']))
{
	#Dane
	$poll = $db->query('SELECT * FROM '.PRE.'polls WHERE access="'
		.$nlang.'" ORDER BY ID DESC LIMIT 1') -> fetch(2);

	#Istnieje? + ID
	if($poll) { $id = $poll['ID']; } else { $content->message(22); exit; }

	#G³osowa³ na...
	$voted = isset($_COOKIE[PRE.'voted']) ? unserialize($_COOKIE[PRE.'voted']) : array();

	#ID u¿ytkownika lub adres IP
	$u = ($poll['ison']==3 && LOGD==1) ? UID : $ip;

	#Mo¿e g³osowaæ?
	if(!in_array($poll['ID'],$voted) && $poll['ison']!=2 && (LOGD==1 || $poll['ison']==1))
	{
		#Je¿eli brak wpisu w bazie, ¿e g³osowa³...
		if(db_count('ID','pollvotes WHERE ID='.$id.' AND user='.$u)==0)
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
				$db->exec('INSERT INTO '.PRE.'pollvotes (user,ID,date) VALUES ('.$u.','.$id.','.$_SERVER['REQUEST_TIME'].')');
				$db->commit();

				#Pobierz odpowiedzi
				$o = $db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$id)->fetchAll(3);

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
	setcookie(PRE.'voted', serialize($voted), time()+7776000);
	
	#JS?
	if(isset($_GET['js']))
	{
		$_GET['id'] = $id; include 'mod/panels/poll.php'; //Wy¶wietl ma³e wyniki
	}
	else
	{
		$content->message(5, MOD_REWRITE ? '/poll/'.$id : 'index.php?co=poll&amp;id='.$id);
	}
}
