<?php /* Wysy³anie PW */
if(iCMS!=1) exit;
$content->info('Modu³ w przebudowie.'); return 1;
#Tablica b³êdów
$error = array();

#Kopia?
$pm_copy = isset($_POST['pm_s']) || (!$_POST && isset($_COOKIE[PRE.'pm_s'])) ? 1 : 0;

#Pobierz wiadomo¶æ - nale¿y do u¿ytkownika?
if($id)
{
	$pm = $db -> query('SELECT p.*,u.login FROM '.PRE.'pms p LEFT JOIN '.PRE.'users u
		ON p.usr=u.ID WHERE p.ID='.$id.' AND p.owner='.UID) -> fetch(2); //ASSOC

	#Nie istnieje?
	if(!$pm) return;
}

#Nowa lub odpowied¼
if(!$id || $pm['st']==2)
{
	$url = '?co=pms&amp;act=e"';
	$content -> title = $lang['write'];
}
#Edytuj
else
{
	$url = '?co=pms&amp;act=e&amp;id='.$id;
	$content -> title = $lang['editpm'];
}

#Wys³ane dane
if($_POST)
{
	#Dane
	$new = array(
		'to'  => $id && $pm['st']==1 ? null : Clean($_POST['to']),
		'txt' => Clean($_POST['txt']),
		'bbc' => isset($cfg['bbcode']) && isset($_POST['pm_bbc']) ? 1 : 0,
		'topic' => empty($_POST['pm_th']) ? $lang['notopic'] : Clean($_POST['pm_th'],50,1)
	);

	#Wy¶lij?
	if(isset($_POST['send']) OR isset($_POST['save']))
	{
		#Nowy obiekt API
		include './mod/pms/api.php';
		$o = new PM;
		$o -> to = $new['to'];
		$o -> text = $new['txt'];
		$o -> topic = $new['topic'];
		$o -> bbcode = $new['bbc'];

		#Start transakcji
		$db -> beginTransaction();
		try
		{
			#Zapisz
			if(isset($_POST['save']) && !$id OR $pm['st']==3)
			{
				if($id) $o -> save(); else $o -> send();
			}
			#Nowa wiadomo¶æ
			if(!$id OR $pm['st']==3)
			{
				$o -> send();
				if($pm['st'] == 3)
				{
					$o -> delete($db->lastInsertId()); //Usuñ z kopii roboczych
				}
				if($pm_copy)
				{
					$o -> save('sent'); //Zapisz do wys³anych
				}
			}
			#Edytuj
			elseif($pm['st']==5)
			{
				
			}
		}
		catch(Exception $e)
		{
			$content->info($e);
		}
	}

	

	#Limit (zapis kopii)
	if(isset($_POST['copy']) && !$id)
	{
		if($pm_ile >= $cfg['pmLimit']) $error[] = $lang['pm_22'];
	}

	#ZAPISZ
	if(!$error && (isset($_POST['save']) OR isset($_POST['send'])))
	{
		#Start
		$db->beginTransaction();

		#Edytuj
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'pms SET topic=:topic, usr=:usr, bbc=:bbc, txt=:txt WHERE ID='.$id);
			$content->info($lang['pm_24']);
		}
		#Nowa
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'pms (topic,usr,owner,st,date,bbc,txt)
				VALUES (:topic,:usr,:owner,:status,'.$_SERVER['REQUEST_TIME'].',:bbc,:txt)');
			$db->exec('UPDATE '.PRE.'users SET pms=pms+1 WHERE ID='.$to_id);

			$pm['owner'] = $to_id; //W³a¶ciciel - odbiorca
			$pm['status'] = 1; //Status - nowy
		}
	}
	if(isset($_POST['send']) && !$error)
	{
		#Start
		$db->beginTransaction();

		#Edytuj
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'pms SET topic=:topic, usr=:usr, bbc=:bbc, txt=:txt WHERE ID='.$id);
			$content->info($lang['pm_24']);
		}
		#Nowa
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'pms (topic,usr,owner,st,date,bbc,txt)
				VALUES (:topic,:usr,:owner,:status,'.$_SERVER['REQUEST_TIME'].',:bbc,:txt)');
			$db->exec('UPDATE '.PRE.'users SET pms=pms+1 WHERE ID='.$to_id);

			$pm['owner'] = $to_id; //W³a¶ciciel - odbiorca
			$pm['status'] = 1; //Status - nowy
		}
		#Dane
		$q->bindValue(':topic',$pm_th);
		$q->bindValue(':usr',UID,1);
		$q->bindValue(':bbc',$pm_bbc,1);
		$q->bindParam(':txt',$pm_txt);
		$q->execute();

		#Do wys³anych?
		if($pm_copy)
		{
			$q->bindValue(':usr',$to_id,1);
			$q->bindValue(':owner',UID,1);
			$q->bindValue(':status',4,1);
			$q->execute();
		}

		#OK
		try
		{
			$db->commit();
			if(!$id) $content->info($lang['pm_23']); else include('./mod/pms/message.php');
			return;
		}
		catch(PDOException $e)
		{
			$content->info('Error: '.$e->errorInfo[0]);
		}
	}

	#Podgl±d
	else
	{
		OpenBox($pm_th,1);
		echo '<tr><td class="txt">';

		#BBCode
		if($pm_bbc)
		{
			require('./lib/bbcode.php');
			echo nl2br(Emots(ParseBBC($pm_txt)));
		}
		else
		{
			echo nl2br(Emots($pm_txt));
		}
		echo '</td></tr>';
		CloseBox();
	}
}

#Pobierz wiadomo¶æ
elseif($id)
{
	$pm = $db
		-> query('SELECT a.*,u.login FROM '.PRE.'pms a LEFT JOIN '.PRE.'users u
			ON a.usr=u.ID WHERE a.ID='.$id.' AND (a.owner='.UID.' OR (a.usr='.UID.' AND a.st=1))')
		-> fetch(2); //ASSOC

	#Dane
	if(!is_numeric($pm['usr'])) { $content->info($lang['noex']); return; }

	#Do szablonu
	$content->data += array(
		'url'    => $url,
		'pm_copy'=> $pm_copy,
		'pm_to'  => $pm['login'],
		'pm_txt' => &$pm['txt'],
		'bbcode' => isset($cfg['bbcode']) ? false : true,
		'pm_bbc' => isset($cfg['bbcode']) ? $pm['bbc'] : 0,
		'pm_th'  => (($pm['st']==2 && strpos($pm['topic'],$lang['re']!==0))?$lang['re']:'').$pm['topic']
	);
}
else
{
	#Odbiorca z GET
	$content->data += array(
		'pm_txt' => '',
		'pm_th'  => '',
		'url'    => $url,
		'pm_copy'=> $pm_copy,
		'bbcode' => isset($cfg['bbcode']) ? true : false,
		'pm_to'  => isset($_GET['a']) ? Clean($_GET['a'],50,1) : '',
		'pm_bbc' => isset($cfg['bbcode']) ? 1 : 0
	);
}

#B³êdy
if($error) $content->info(join('<br /><br />',$error));

#Edytor JS
if($cfg['bbcode']==1)
{
	$content->addScript(LANG_DIR.'edit.js');
	$content->addScript('lib/editor.js');
	$content->addScript('cache/emots.js');
}

#Formularz
$content->file[] = 'pms_posting';