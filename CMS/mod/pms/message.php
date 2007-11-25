<?php
if(iCMS!=1) exit;
if($id=='') exit('ID error!');

#Odczyt
$res=$db->query('SELECT * FROM '.PRE.'pms WHERE (owner='.UID.' OR (usr='.UID.' AND st=1)) AND ID='.$id);
$pm=$res->fetch(2);
$res=null;

#Brak?
if(!$pm)
{
	Info($lang['pms_9']);
}
else
{
	#BBCode
	if($pm['bbc']==1 && $cfg['bbc']==1)
	{
		require_once('lib/bbcode.php');
		$pm['txt']=ParseBBC($pm['txt']);
	}
	
	#Treœæ - emoty
	$pm['txt']=nl2br(Emots($pm['txt']));

	#Przeczytana?
	if($pm['st']==1 && $pm['owner']==UID)
	{
		$db->exec('UPDATE '.PRE.'pms SET st=2 WHERE ID='.$pm['ID']);
		$db->exec('UPDATE '.PRE.'users SET pms=pms-1 WHERE ID='.$pm['owner']);
		--$user[UID]['pms'];
		$pm['st']=2;
	}

	#Data, autor
	$pm_date=genDate($pm['date']);
	$pm_user=Autor($pm['usr']);
	$pm_edit=($pm['st']==2)?$lang['pm_10']:$lang['edit'];
	
	#Skrypt JS
	?>

	<script type="text/javascript">
	<!--
	var pm=new Request('request.php?co=pms&act=1&id=<?=$id?>','main','');
	function PM_Edit() { location='?co=pms&act=e&id=<?=$id?>'; }
	function PM_Del() { pm.add('act2','del'); pm.run(1); }
	function PM_Arch() { pm.add('act2','arch'); pm.run(1); }
	-->
	</script>

	<?php
	#Szablon
	include($catst.'pm-view.php');
}
?>
