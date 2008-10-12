<?php /* Operacje na PW */
if(iCMS!=1) exit;

#Usuñ 1
if(isset($_POST['del']) && !is_array($_POST['del']))
{
	$q = 'ID='.(int)$_POST['del'];
}

#Z listy
elseif(isset($_POST['x']) && count($_POST['x'])>0)
{
	$list = array();
	foreach($_POST['x'] as $key=>$val)
	{
		if(is_numeric($key)) $list[] = $key;
	}
	$q = 'ID IN ('.join(',', $list).')';
	unset($list,$key,$val);
}

else return;

#START
$db->beginTransaction();

#Usuñ
if(isset($q))
{
	#Pobierz w³a¶cicieli
	$res = $db->query('SELECT owner FROM '.PRE.'pms WHERE st=1 AND (usr='.UID.' OR owner='.UID.') AND '.$q);
	$res->setFetchMode(7,0); //Column
	$users = array();

	foreach($res as $x)
	{
		if(isset($users[$x])) ++$users[$x]; else $users[$x] = 1;
  }
	$res = null;

	#Zmniejsz ilo¶æ PM
  foreach($users as $u=>$x)
  {
		$db->exec('UPDATE '.PRE.'users SET pms=pms-'.$x.' WHERE ID='.$u);
  }
  unset($u,$x,$users);
	$db->exec('DELETE FROM '.PRE.'pms WHERE (owner='.UID.' OR (usr='.UID.' AND st=1)) AND '.$q);
}
$db->commit();

unset($q);
require './mod/pms/list.php';