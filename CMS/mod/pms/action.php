<?php
/*Usuwanie i archiwizacja PM*/
if(iCMS!=1) exit;
$q='';

#Usuñ 1
if($id)
{
	$q='ID='.$id;
}

#Z listy
elseif(is_array($_POST['pmdel']))
{
	if(count($_POST['pmdel'])>0)
	{
		$pms=array();
		foreach($_POST['pmdel'] as $key=>$val)
		{
			if(is_numeric($key)) $pms[]='ID='.$key;
		}
		$q='('.join(' OR ',$pms).')';
		unset($pms,$key,$val);
	}
}

#START
if($q) $db->beginTransaction();

#Do archiwum
if($q && $_GET['act2']=='arch')
{
	$db->exec('UPDATE '.PRE.'pms SET st=3 WHERE st=2 AND owner='.UID.' AND '.$q);
	$id=3;
}
#Usuñ
elseif($q)
{
	#Pobierz w³a¶cicieli
	$res=$db->query('SELECT owner FROM '.PRE.'pms WHERE st=1 && (usr='.UID.' OR owner='.UID.') && '.$q);
	$res->setFetchMode(7); //Column
	$pms=array();

	foreach($res as $u)
	{
		if(!isset($pms[$u])) $pms[$u]=1; else ++$pms[$u];
  }
	$res=null;

	#Zmniejsz ilo¶æ PM
  foreach($pms as $key=>$val)
  {
		db_q('UPDATE '.PRE.'users SET pms=pms-'.$val.' WHERE ID='.$key);
  }
  unset($key,$val,$pms,$u);
	$db->exec('DELETE FROM '.PRE.'pms WHERE (owner='.UID.' OR (usr='.UID.' AND st=1)) AND '.$q);
}

if($q)
{
	$db->commit();
	unset($q);
	require('./mod/pms/list.php');
}
?>
