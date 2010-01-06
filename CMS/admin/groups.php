<?php
if(iCMSa!=1 || !admit('G')) exit;
require LANG_DIR.'admAll.php'; //Jêzyk

#Usuñ
if($_POST)
{
	$x = GetID(true);
	if(isset($_POST['del']))
	{
		$db->beginTransaction();
		$db->exec('DELETE FROM '.PRE.'groups WHERE ID IN ('.$x.')');
		$db->exec('DELETE FROM '.PRE.'groupuser WHERE g IN ('.$x.')');
		$db->commit();
	}
}

#Info
$content->info($lang['groupInfo'], array(url('editGroup','','admin')=>$lang['addGroup']));

#Odczyt
$res = $db->query('SELECT ID,name,opened FROM '.PRE.'groups');
$res-> setFetchMode(3); //NUM

#Lista
$group = array();
$num = 0;

foreach($res as $g)
{
	$group[] = array(
		'id'     => $g[0],
		'num'    => ++$num,
		'url'    => url('group/'.$g[0]),
		'edit'   => url('editGroup/'.$g[0], '', 'admin'),
		'title'  => $g[1],
		'opened' => $g[2] ? $lang['yes'] : $lang['no']
	);
}

$res = null;
$content->data['group'] = &$group;