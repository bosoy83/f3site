<?php
if(iCMSa!=1) exit;
require LANG_DIR.'poll.php'; //Jêzyk

#Tytu³ strony
$content->title = $id ? $lang['editPoll'] : $lang['addPoll'];

#Zapis
if($_POST)
{
	#Dane
	$poll = array(
		'name' => Clean($_POST['name']),
		'q'    => Clean($_POST['q']),
		'ison' => (int)$_POST['ison'],
		'type' => (int)$_POST['type'],
		'access' => ctype_alpha($_POST['access']) ? $_POST['access'] : $nlang
	);

	$num  = count($_POST['an']);
	$keep = array();
	$an = array();

	#Odpowiedzi
	for($i=0; $i<$num; ++$i)
	{
		if(!$id || empty($_POST['id'][$i]))
		{
			$an[] = array(null, Clean($_POST['an'][$i]));
		}
		else
		{
			$an[] = array( (int)$_POST['id'][$i], Clean($_POST['an'][$i]) );
			$keep[] = (int)$_POST['id'][$i];
		}
	}

	#START
	try
	{
		$db->beginTransaction();

		#Edycja + usuñ zlikwidowane odpowiedzi
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'polls SET name=:name, q=:q, ison=:ison,
				type=:type, access=:access WHERE ID='.$id);
			$db->exec('DELETE FROM '.PRE.'answers WHERE ID NOT IN ('.join(',',$keep).') AND IDP='.$id);
		}
		#Nowy
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'polls (name,q,ison,type,num,access,date)
				VALUES (:name,:q,:ison,:type,0,:access,CURRENT_DATE)');
		}
		$q->execute($poll);

		#Nowy ID
		if(!$id) $id  = $db->lastInsertId();

		#Odpowiedzi
		$q = $db->prepare('REPLACE INTO '.PRE.'answers (ID,IDP,a) VALUES (?,'.$id.',?)');

		for($i=0; $i<$num; $i++)
		{
			$q->execute($an[$i]);
		}

		#Aktualizuj cache najnowszych sond
		include './mod/polls/poll.php';
		RebuildPoll();

		#ZatwierdŸ
		$db->commit();
		$content->info($lang['saved'], array('?a=editPoll' => $lang['addPoll'],
			MOD_REWRITE ? '/poll/'.$id : 'index.php?co=poll&amp;id='.$id => $poll['name']));
		return 1;
	}
	catch(PDOException $e)
	{
		$content->info($lang['error'].$e->errorInfo[2]);
	}
}

#Form
elseif($id)
{
	$poll = $db->query('SELECT * FROM '.PRE.'polls WHERE ID='.$id)->fetch(2); //ASSOC
	$an = $db->query('SELECT ID,a FROM '.PRE.'answers WHERE IDP='.$id.' ORDER BY seq') -> fetchAll(3);
}
else
{
	$poll = array('name'=>'', 'q'=>'', 'type'=>1, 'ison'=>1, 'access'=>$nlang);
	$an = array( array(0, '') );
}

#Szablon
$content->addScript('lib/forms.js');
$content->data = array(
	'url'  => '?a=editPoll'.(($id) ? '&amp;id='.$id : ''),
	'langs'=> ListBox('lang', 1, ($id) ? $poll['access'] : $nlang),
	'poll' => &$poll,
	'item' => &$an
);