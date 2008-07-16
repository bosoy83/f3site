<?php
if(iCMSa!=1 || !Admit('Q')) exit;
require LANG_DIR.'poll.php';

#Operacje
if($_POST && $x = GetID(true))
{
	if(isset($_POST['del']))
	{
		$db->exec('DELETE FROM '.PRE.'polls WHERE ID IN ('.$x.')');
		$db->exec('DELETE FROM '.PRE.'answers WHERE IDP IN ('.$x.')');
		$db->exec('DELETE FROM '.PRE.'comms WHERE TYPE=15 AND CID IN ('.$x.')');
		$db->exec('DELETE FROM '.PRE.'pollvotes WHERE ID IN ('.$x.')');
	}
	elseif($_POST['reset'])
	{
		$db->exec('UPDATE '.PRE.'answers SET num=0 WHERE IDP IN ('.$x.')');
		$db->exec('UPDATE '.PRE.'polls SET num=0 WHERE ID IN ('.$x.')');
		$db->exec('DELETE FROM '.PRE.'pollvotes WHERE ID IN ('.$x.')');
	}
	unset($x);
}

#Info
$content->info($lang['ipoll'], array('?a=editPoll'=>$lang['addPoll']));

#Odczyt
$res = $db->query('SELECT ID,name,num,access FROM '.PRE.'polls ORDER BY ID DESC');
$res->setFetchMode(3); //Num

#Lista
$total = 0;
$polls = array();

foreach($res as $x)
{
	$polls[] = array(
		'num'  => ++$total,
		'url'  => MOD_REWRITE ? '/poll/'.$x[0] : 'index.php?co=poll&amp;id='.$x[0],
		'id'   => $x[0],
		'title'  => $x[1],
		'votes'  => $x[2],
		'access' => $x[3]
	);
}

$res = null;
$content->data['polls'] = $polls;
