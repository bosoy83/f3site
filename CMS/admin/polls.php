<?php
if(iCMSa!=1 || !Admit('Q')) exit;
require LANG_DIR.'f3s.php';

#Operacje
if($_POST)
{
	if(count($_POST['chk'])>0)
	{
		$_q=GetIDs($_POST['chk']);
		if($_POST['delp'])
		{
			$db->exec('DELETE FROM '.PRE.'polls WHERE ID IN ('.join(',',$_q).')');
			$db->exec('DELETE FROM '.PRE.'answers WHERE IDP IN ('.join(',',$_q).')');
			$db->exec('DELETE FROM '.PRE.'comms WHERE th="12_'.join('" || th="12_',$_q).'"');
			$db->exec('DELETE FROM '.PRE.'pollvotes WHERE ID IN ('.join(',',$_q).')');
		}
		elseif($_POST['zerp'])
		{
			$db->exec('UPDATE '.PRE.'answers SET num=0 WHERE IDP IN ('.join(',',$_q).')');
			$db->exec('UPDATE '.PRE.'polls SET num=0 WHERE ID IN ('.join(',',$_q).')');
			$db->exec('DELETE FROM '.PRE.'pollvotes WHERE ID IN ('.join(',',$_q).')');
		}
		unset($_q);
	}
}

#Info
$content->info($lang['ipoll'], array('?a=editpoll'=>$lang['addpoll']));

#Odczyt
$res=$db->query('SELECT ID,name,num,access FROM '.PRE.'polls ORDER BY ID DESC');
$res->setFetchMode(3); //Num

#Lista
$total = 0;
$polls = array();

foreach($res as $poll)
{
	$polls[] = array(
		'num'  => ++$total,
		'ID'   => $poll[0],
		'title'  => $poll[1],
		'votes'  => $poll[2],
		'access' => $poll[3]
	);
}

$res = null;
$content->data['polls'] =& $polls;
?>
