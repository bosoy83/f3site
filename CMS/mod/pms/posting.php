<?php /*Wysy³anie PW - modu³ do przebudowania*/
if(iCMS!=1) exit;

#Tablica b³êdów
$error = array();

#Kopia?
$pm_copy = isset($_POST['pm_s']) || (!$_POST && isset($_COOKIE[PRE.'pm_s'])) ? 1 : 0;

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
	#Do, Temat, BBCode
	$pm_to  = Clean($_POST['pm_to']);
	$pm_th  = empty($_POST['pm_th']) ? $lang['notopic'] : Clean($_POST['pm_th'],50,1);
	$pm_bbc = isset($cfg['bbcode']) && isset($_POST['pm_bbc']) ? 1 : 0;

	#Tre¶æ
	if(isset($_POST['pm_txt'][20001]))
	{
		$error[] = $lang['pm_18'];
	}
	$pm_txt = Clean($_POST['pm_txt'],0,1);

	#ID odbiorcy $to_id
	$to_id = (int)db_get('ID','users WHERE login='.$db->quote($pm_to));
	if(!$to_id)
	{
		$error[] = $lang['pm_20'];
	}
	else
	{
		#Limit
		if(db_count('ID','pms WHERE owner='.$to_id) >= $cfg['pmLimit'])
		{
			$error[] = $lang['pm_21'];
		}
	}

	#Limit (zapis kopii)
	if(isset($_POST['pm_s']) && !$id)
	{
		if($pm_ile >= $cfg['pmLimit']) $error[]=$lang['pm_22'];
	}

	#ZAPISZ
	if(isset($_POST['save']) && !$error)
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

			#Dane
			$q->bindValue(':owner',$to_id,1); //1 = INT
			$q->bindValue(':status',1,1);
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
$content->data['file'] = 'pms_posting.html';
?>
