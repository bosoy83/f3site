<?php
if(iCMSa!=1 || !Admit('UG')) exit;
require LANG_DIR.'adm_o.php'; //Jêzyk

#Usuñ
if($_POST)
{
	unset($_POST['x'][1]); //Nie mo¿na usun¹æ domyœlnej grupy
	$x = GetID(true);

	if(isset($_POST['del']))
	{
		$db->exec('DELETE FROM '.PRE.'groups WHERE ID IN ('.$x.')');
		$db->exec('UPDATE '.PRE.'users SET gid=1 WHERE gid IN ('.$x.')');
	}
}

#Info
$content->info($lang['ugrw'], array('?a=editgroup'=>$lang['gradd']));

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
