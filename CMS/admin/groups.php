<?php
if(iCMSa!=1 || !Admit('G')) exit;
require LANG_DIR.'admAll.php'; //J�zyk

#Usu�
if($_POST)
{
	unset($_POST['x'][1]); //Nie mo�na usun�� domy�lnej grupy
	$x = GetID(true);

	if(isset($_POST['del']))
	{
		$db->exec('DELETE FROM '.PRE.'groups WHERE ID IN ('.$x.')');
		$db->exec('UPDATE '.PRE.'users SET gid=1 WHERE gid IN ('.$x.')');
	}
}

#Info
$content->info($lang['groupInfo'], array('?a=editGroup'=>$lang['addGroup']));

#Odczyt
$res = $db->query('SELECT ID,name,opened FROM '.PRE.'groups');
$res-> setFetchMode(3); //NUM

#Lista
$group = array();
$num = 0;

foreach($res as $g)
{
	$group[] = array(
		'id'  => $g[0],
		'num' => ++$num,
		'title'  => $g[1],
		'opened' => ($g[2]===1) ? $lang['yes'] : $lang['no']
	);
}

$res = null;
$content->data['group'] = $group;